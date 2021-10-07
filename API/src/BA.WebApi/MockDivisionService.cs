using System.Collections.Generic;
using BA.Domain.Division;
using System.Linq;
using BA.Domain.Division.ViewModel;

namespace BA.WebApi
{
    public class MockDivisionService : IDivisionService
    {
        private List<DivisionViewModel> getMocks()
        {
            var list = new List<DivisionViewModel>();

            list.Add(new DivisionViewModel(){
                DivisionId = 10
            });

            return list;
        }

        public IEnumerable<DivisionViewModel> GetAll()
        {
            return this.getMocks();
        }

        public DivisionViewModel GetDivisionById(int divisionId)
        {
            var division = this.getMocks().Where(x => x.DivisionId == divisionId).First();

            division.Catalogs = division.GetCatalogues();

            return division;
        }
    }
}