
using System.Collections.Generic;
using BA.DataAccess;
using System.Linq;
using BA.Domain.Division.ViewModel;

namespace BA.Domain.Services
{
    public class DivisionService : BA.Domain.Division.IDivisionService
    {
        private readonly BaContext context;

        public DivisionService(BaContext context)
        {
            this.context = context;
        }

        public IEnumerable<DivisionViewModel> GetAll()
        {
            return this.context.Divisions
                .ToList()
                .Select(x => new DivisionViewModel{
                    DivisionId = x.DivisionId,
                    Name = x.Name
                });
        }

        public DivisionViewModel GetDivisionById(int divisionId)
        {
            return this.context.Divisions.Where(x => x.DivisionId == divisionId)
                .Select(x => new DivisionViewModel{
                    DivisionId = x.DivisionId,
                    Name = x.Name
                })
                .First();
        }
    }
}