namespace BA.Domain.Division
{
    using BA.Domain.Division.ViewModel;
    using System.Collections.Generic;

    public interface IDivisionService
    {
        DivisionViewModel GetDivisionById(int divisionId);

        IEnumerable<DivisionViewModel> GetAll();
    } 
}