<?php
namespace BA\Slider\Controller\Adminhtml\Slider;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action\Context;
use BA\Slider\Model\RequestFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Backend\Model\Session;

class Save extends \Magento\Backend\App\Action
{
    protected $requestModel;
    protected $uploaderFactory;
    protected $sessionData;

    public function __construct(
        Context $context,
        RequestFactory $requestModel,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $fileSystem,
        Session $sessionData
    ) {
        $this->requestModel = $requestModel;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->fileSystem = $fileSystem;
        $this->sessionData = $sessionData;
        parent::__construct($context);
    }

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $profileImage = $this->getRequest()->getFiles('profile');
        $mobImage = $this->getRequest()->getFiles('mobimage');

        if ($data) {
            /** @var BA\Slider\Model\RequestFactory */
            $model = $this->requestModel->create();
            $uploadImageName= $uploadMobName ='';
            if ($profileImage['name'] != '') {
                $uploadImageName = $this->uploadImage('profile', 'MainImage', $data, 'image');
            }
            if ($uploadImageName || (isset($data['profile']['delete']) && $data['profile']['delete'])) {
                $data['image'] = $uploadImageName;
            }
            else {
                unset($data['image']);
            }
            if ($mobImage['name'] != '') {
                $uploadMobName = $this->uploadImage('mobimage', 'MobImage', $data, 'mobimage');
            }

            $id = $this->getRequest()->getParam('id');
          
            if ($id) {
                $model->load($id);
            }
            if ($uploadMobName || (isset($data['mobimage']['delete']) && $data['mobimage']['delete'])) {
                $data['mobimage'] = $uploadMobName;
            }
            else {
                unset($data['mobimage']);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Data Saved.'));
                $this->sessionData->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $x = $data;
            $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            return;
        }
        $this->_redirect('*/*/');
    }

    private function uploadImage($fileId, $folderName, $data, $fieldname)
    {
        try {
            /** @var \Magento\Framework\ObjectManagerInterface $uploader */
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

            /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapterFactory */
            $imageAdapterFactory = $this->adapterFactory->create();
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);
            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
            $result = $uploader->save($mediaDirectory->getAbsolutePath('Slider/'.$folderName));
           // $data[$fieldname] = 'Slider/'.$folderName.'/' . $result['file'];
            $uploadImageName = 'Slider/'.$folderName.'/' . $result['file'];
          //  $model->setProfile('Slider/'.$folderName.'/'.$result['image']); //Database field name
        } catch (\Exception $e) {
            if ($e->getCode() == 0) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        return $uploadImageName;
    }
}
