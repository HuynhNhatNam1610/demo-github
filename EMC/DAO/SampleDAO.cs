using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using EMC.DTO;

namespace EMC.DAO
{
    public class SampleDAO
    {
        private static SampleDAO instance;

        public static SampleDAO Instance
        {
            get { if (instance == null) instance = new SampleDAO(); return SampleDAO.instance; }
            private set { SampleDAO.instance = value; }
        }

        public SampleDAO() { }

        public List<Sample> GetListSamples(string orderBy = "sample_code")
        {
            Dictionary<string, object> parameters = new Dictionary<string, object>
    {
        { "@orderBy", orderBy }
    };
            DataTable data = DataProvider.Instance.ExecuteProcedureWithParameter("GetAllSamples", parameters);

            List<Sample> list = new List<Sample>();
            foreach (DataRow row in data.Rows)
            {
                list.Add(new Sample(row));
            }

            return list;
        }
    }
}
