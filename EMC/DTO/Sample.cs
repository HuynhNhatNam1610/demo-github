using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace EMC.DTO
{
    public class Sample
    {
        public Sample(int contractID, string sampleCode, string sampleType, string description, string location, DateTime createdAt) 
        { 
            this.ContractID = contractID;
            this.SampleCode = sampleCode;
            this.SampleType = sampleType;
            this.Description = description;
            this.Location = location;
            this.CreatedAt = createdAt;
        }

        public Sample(DataRow row)
        {
            this.ContractID = (int)row["contract_id"];
            this.SampleCode = row["sample_code"].ToString();
            this.SampleType = row["sample_type"].ToString();
            this.Description = row["description"].ToString();
            this.Location = row["location"].ToString();
            this.CreatedAt = row["created_at"] != DBNull.Value ? (DateTime)row["created_at"] : DateTime.MinValue;
        }

        private int contractID;

        public int ContractID {
            get { return contractID; }
            set { contractID = value; }
        }

        private string sampleCode;

        public string SampleCode
        {
            get { return sampleCode; }  
            set { sampleCode = value; }
        }

        private string sampleType;

        public string SampleType
        {
            get { return sampleType; }  
            set { sampleType = value; }
        }

        private string description;

        public string Description
        {
            get { return description; }
            set { description = value; }
        }

        private string location;

        public string Location
        {
            get { return location; }
            set { location = value; }
        }

        private DateTime createdAt;

        public DateTime CreatedAt
        {
            get { return createdAt; }
            set { createdAt = value; }
        }
    }
}
