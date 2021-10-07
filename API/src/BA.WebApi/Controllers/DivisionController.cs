using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using BA.Domain.Division;
using BA.Domain.Division.ViewModel;
using BA.DataAccess;

namespace BA.WebApi.Controllers
{
    [ApiController]
    [Route("[controller]")]
    public class DivisionController : ControllerBase
    {
        private readonly IDivisionService divisionService;

        public DivisionController(IDivisionService divisionService, BaContext context)
        {
            this.divisionService = divisionService;
        }

        
        [HttpGet]
        public IList<DivisionViewModel> Get()
        {
            return this.divisionService.GetAll().ToList();
        }

        [HttpGet]
        [Route("{divisionId}")]
        public DivisionViewModel Get(int divisionId)
        {
            return this.divisionService.GetDivisionById(divisionId);
        }
    }
}
