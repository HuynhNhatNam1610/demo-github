using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace EMC.UI.Forms
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            userControl11.LabelClicked += Uc_LabelClicked;

        }

        private void Uc_LabelClicked(object sender, EventArgs e)
        {
            MessageBox.Show("Form cha nhận được: Panel trong UserControl vừa click!");
        }

        private void userControl11_Load(object sender, EventArgs e)
        {

        }
    }
}
