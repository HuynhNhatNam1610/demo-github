using EMC.UI.Helpers;
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
    public partial class fReview : Form
    {
        public fReview()
        {
            InitializeComponent();
        }

        private void Form2_Load(object sender, EventArgs e)
        {
            UIHelpers.LoadImage(pbLogo, @"UI\Resources\images\logo.png", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbBackground, @"UI\Resources\images\envir2.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbHeroi1, @"UI\Resources\images\quantrac.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbHeroi2, @"UI\Resources\images\quantrac1.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbHeroi3, @"UI\Resources\images\quantrac2.jpg", PictureBoxSizeMode.StretchImage);
            UIHelpers.LoadImage(pbBanner, @"UI\Resources\images\saveus.png", PictureBoxSizeMode.StretchImage);
      
      

            //label2.Parent = pbBanner;
            panel3.Parent = pbBackground;
            //label15.Parent = pbBanner;
            label15.Parent = pbBackground;
            label4.Parent = pbBanner;
            pbLogo.Parent = pbBanner;
            // Điều chỉnh Location để bù trừ vị trí của pbBanner (ngăn bị đẩy xuống)
            //label2.Location = new Point(label2.Left - pbBanner.Left, label2.Top - pbBanner.Top);
            panel3.Location = new Point(panel3.Left - pbBackground.Left, panel3.Top - pbBackground.Top);
            label15.Location = new Point(label15.Left - pbBackground.Left, label15.Top - pbBackground.Top);
            //label15.Location = new Point(label15.Left - pbBanner.Left, label15.Top - pbBanner.Top);
        }

        private void pbBanner_Click(object sender, EventArgs e)
        {

        }

        private void pbBackground_Click(object sender, EventArgs e)
        {

        }
    }
}