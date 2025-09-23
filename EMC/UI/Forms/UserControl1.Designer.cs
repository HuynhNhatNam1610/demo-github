namespace EMC.UI.Forms
{
    partial class UserControl1
    {
        /// <summary> 
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary> 
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Component Designer generated code

        /// <summary> 
        /// Required method for Designer support - do not modify 
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            pSidebar = new Panel();
            cpbLogo = new EMC.UI.Controls.CirclePictureBox();
            roundedButton1 = new EMC.UI.Controls.RoundedButton();
            line1 = new EMC.UI.Controls.Line();
            label4 = new Label();
            label3 = new Label();
            label2 = new Label();
            label1 = new Label();
            pSidebar.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)cpbLogo).BeginInit();
            SuspendLayout();
            // 
            // pSidebar
            // 
            pSidebar.BackColor = Color.DeepSkyBlue;
            pSidebar.Controls.Add(cpbLogo);
            pSidebar.Controls.Add(roundedButton1);
            pSidebar.Controls.Add(line1);
            pSidebar.Controls.Add(label4);
            pSidebar.Controls.Add(label3);
            pSidebar.Controls.Add(label2);
            pSidebar.Controls.Add(label1);
            pSidebar.Location = new Point(0, 0);
            pSidebar.Name = "pSidebar";
            pSidebar.Size = new Size(298, 672);
            pSidebar.TabIndex = 1;
            pSidebar.Paint += pSidebar_Paint;
            // 
            // cpbLogo
            // 
            cpbLogo.BackColor = Color.Transparent;
            cpbLogo.Location = new Point(11, 85);
            cpbLogo.Name = "cpbLogo";
            cpbLogo.Size = new Size(85, 85);
            cpbLogo.TabIndex = 2;
            cpbLogo.TabStop = false;
            // 
            // roundedButton1
            // 
            roundedButton1.BackColor = Color.DeepSkyBlue;
            roundedButton1.BackgroundImageLayout = ImageLayout.Zoom;
            roundedButton1.BorderColor = Color.White;
            roundedButton1.BorderRadius = 10;
            roundedButton1.BorderSize = 1;
            roundedButton1.FlatAppearance.BorderSize = 0;
            roundedButton1.FlatStyle = FlatStyle.Flat;
            roundedButton1.Font = new Font("Segoe UI", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            roundedButton1.ForeColor = Color.White;
            roundedButton1.Location = new Point(11, 15);
            roundedButton1.Name = "roundedButton1";
            roundedButton1.Size = new Size(51, 47);
            roundedButton1.TabIndex = 1;
            roundedButton1.Text = "☰";
            roundedButton1.UseVisualStyleBackColor = false;
            // 
            // line1
            // 
            line1.LineColor = Color.White;
            line1.LineWidth = 1;
            line1.Location = new Point(-3, 176);
            line1.Name = "line1";
            line1.Size = new Size(301, 29);
            line1.TabIndex = 1;
            line1.Text = "line1";
            // 
            // label4
            // 
            label4.AutoSize = true;
            label4.BackColor = Color.Transparent;
            label4.Font = new Font("Segoe UI", 10.8F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label4.ForeColor = Color.White;
            label4.Location = new Point(11, 310);
            label4.Name = "label4";
            label4.Size = new Size(135, 25);
            label4.TabIndex = 3;
            label4.Text = "🔔 Thông báo";
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.BackColor = Color.Transparent;
            label3.Font = new Font("Segoe UI", 10.8F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label3.ForeColor = Color.White;
            label3.Location = new Point(11, 259);
            label3.Name = "label3";
            label3.Size = new Size(128, 25);
            label3.TabIndex = 2;
            label3.Text = "📄 Hợp đồng";
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.BackColor = Color.Transparent;
            label2.Font = new Font("Segoe UI", 10.8F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label2.ForeColor = Color.White;
            label2.Location = new Point(11, 208);
            label2.Name = "label2";
            label2.Size = new Size(215, 25);
            label2.TabIndex = 1;
            label2.Text = "🏢 Hồ sơ doanh nghiệp";
            label2.Click += label2_Click;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.BackColor = Color.Transparent;
            label1.Font = new Font("Segoe UI", 15F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label1.ForeColor = Color.White;
            label1.Location = new Point(102, 111);
            label1.Name = "label1";
            label1.Size = new Size(149, 35);
            label1.TabIndex = 1;
            label1.Text = "EMC Group";
            // 
            // UserControl1
            // 
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            Controls.Add(pSidebar);
            Name = "UserControl1";
            Size = new Size(298, 673);
            pSidebar.ResumeLayout(false);
            pSidebar.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)cpbLogo).EndInit();
            ResumeLayout(false);
        }

        #endregion

        private Panel pSidebar;
        private Controls.CirclePictureBox cpbLogo;
        private Controls.RoundedButton roundedButton1;
        private Controls.Line line1;
        private Label label4;
        private Label label3;
        private Label label2;
        private Label label1;
    }
}
