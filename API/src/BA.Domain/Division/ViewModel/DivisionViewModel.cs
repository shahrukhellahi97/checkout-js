namespace BA.Domain.Division.ViewModel
{
    using System.Collections.Generic;

    public class DivisionViewModel
    {
        public int DivisionId { get; set; }

        public string Name { get; set; }

        public List<CatalogViewModel> Catalogs { get; set;}

        public List<CatalogViewModel> GetCatalogues()
        {
            return new List<CatalogViewModel>();
        }
    }
}