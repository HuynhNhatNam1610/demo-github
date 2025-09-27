using static System.Windows.Forms.VisualStyles.VisualStyleElement.ListView;

namespace EMC.UI.Forms
{
    partial class fPlanning
    {
        private System.ComponentModel.IContainer components = null;

        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        private void InitializeComponent()
        {
            components = new System.ComponentModel.Container();
            Guna.UI2.WinForms.Suite.CustomizableEdges customizableEdges1 = new Guna.UI2.WinForms.Suite.CustomizableEdges();
            Guna.UI2.WinForms.Suite.CustomizableEdges customizableEdges2 = new Guna.UI2.WinForms.Suite.CustomizableEdges();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(fPlanning));
            DataGridViewCellStyle dataGridViewCellStyle1 = new DataGridViewCellStyle();
            DataGridViewCellStyle dataGridViewCellStyle2 = new DataGridViewCellStyle();
            DataGridViewCellStyle dataGridViewCellStyle3 = new DataGridViewCellStyle();
            panel1 = new Panel();
            label5 = new Label();
            cpbAvatar = new EMC.UI.Controls.CirclePictureBox();
            lFullname = new Label();
            CustomGradientPanel1 = new Panel();
            guna2ComboBox1 = new Guna.UI2.WinForms.Guna2ComboBox();
            roundedButton4 = new EMC.UI.Controls.RoundedButton();
            label6 = new Label();
            roundedButton3 = new EMC.UI.Controls.RoundedButton();
            roundedButton2 = new EMC.UI.Controls.RoundedButton();
            roundedTextBox1 = new EMC.UI.Controls.RoundedTextBox();
            dgvSamples = new DataGridView();
            contractCode = new DataGridViewTextBoxColumn();
            sampleCode = new DataGridViewTextBoxColumn();
            sampleType = new DataGridViewTextBoxColumn();
            sampleDescription = new DataGridViewTextBoxColumn();
            sampleLocation = new DataGridViewTextBoxColumn();
            createdAt = new DataGridViewTextBoxColumn();
            ThaoTac = new DataGridViewButtonColumn();
            sampleStatus = new DataGridViewTextBoxColumn();
            userDropdownMenu = new ContextMenuStrip(components);
            viewProfileItem = new ToolStripMenuItem();
            logoutItem = new ToolStripMenuItem();
            pSidebar = new Panel();
            cpbLogo = new EMC.UI.Controls.CirclePictureBox();
            roundedButton1 = new EMC.UI.Controls.RoundedButton();
            line1 = new EMC.UI.Controls.Line();
            label4 = new Label();
            label3 = new Label();
            label2 = new Label();
            label1 = new Label();
            guna2AnimateWindow1 = new Guna.UI2.WinForms.Guna2AnimateWindow(components);
            panel1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)cpbAvatar).BeginInit();
            CustomGradientPanel1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)dgvSamples).BeginInit();
            userDropdownMenu.SuspendLayout();
            pSidebar.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)cpbLogo).BeginInit();
            SuspendLayout();
            // 
            // panel1
            // 
            panel1.BackColor = Color.White;
            panel1.Controls.Add(label5);
            panel1.Controls.Add(cpbAvatar);
            panel1.Controls.Add(lFullname);
            panel1.Controls.Add(CustomGradientPanel1);
            panel1.Dock = DockStyle.Fill;
            panel1.Location = new Point(0, 0);
            panel1.Name = "panel1";
            panel1.Size = new Size(1502, 674);
            panel1.TabIndex = 2;
            // 
            // label5
            // 
            label5.AutoSize = true;
            label5.Font = new Font("Segoe UI", 13.8F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label5.Location = new Point(326, 14);
            label5.Name = "label5";
            label5.Size = new Size(278, 31);
            label5.TabIndex = 5;
            label5.Text = "Quản lý mẫu môi trường";
            // 
            // cpbAvatar
            // 
            cpbAvatar.Anchor = AnchorStyles.Top | AnchorStyles.Right;
            cpbAvatar.BackColor = Color.Transparent;
            cpbAvatar.BorderColor = Color.Transparent;
            cpbAvatar.Location = new Point(1447, 8);
            cpbAvatar.Name = "cpbAvatar";
            cpbAvatar.Size = new Size(34, 34);
            cpbAvatar.TabIndex = 4;
            cpbAvatar.TabStop = false;
            // 
            // lFullname
            // 
            lFullname.Anchor = AnchorStyles.Top | AnchorStyles.Right;
            lFullname.AutoSize = true;
            lFullname.BackColor = Color.Transparent;
            lFullname.CausesValidation = false;
            lFullname.Font = new Font("Segoe UI", 9F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lFullname.Location = new Point(1309, 14);
            lFullname.Name = "lFullname";
            lFullname.Size = new Size(132, 20);
            lFullname.TabIndex = 3;
            lFullname.Text = "Huỳnh Nhật Nam";
            // 
            // CustomGradientPanel1
            // 
            CustomGradientPanel1.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            CustomGradientPanel1.BackColor = Color.White;
            CustomGradientPanel1.Controls.Add(guna2ComboBox1);
            CustomGradientPanel1.Controls.Add(roundedButton4);
            CustomGradientPanel1.Controls.Add(label6);
            CustomGradientPanel1.Controls.Add(roundedButton3);
            CustomGradientPanel1.Controls.Add(roundedButton2);
            CustomGradientPanel1.Controls.Add(roundedTextBox1);
            CustomGradientPanel1.Controls.Add(dgvSamples);
            CustomGradientPanel1.Location = new Point(321, 48);
            CustomGradientPanel1.Name = "CustomGradientPanel1";
            CustomGradientPanel1.Size = new Size(1181, 620);
            CustomGradientPanel1.TabIndex = 1;
            // 
            // guna2ComboBox1
            // 
            guna2ComboBox1.BackColor = Color.Transparent;
            guna2ComboBox1.BorderRadius = 10;
            guna2ComboBox1.CustomizableEdges = customizableEdges1;
            guna2ComboBox1.DrawMode = DrawMode.OwnerDrawFixed;
            guna2ComboBox1.DropDownStyle = ComboBoxStyle.DropDownList;
            guna2ComboBox1.FocusedColor = Color.FromArgb(94, 148, 255);
            guna2ComboBox1.FocusedState.BorderColor = Color.FromArgb(94, 148, 255);
            guna2ComboBox1.Font = new Font("Segoe UI", 10F);
            guna2ComboBox1.ForeColor = Color.FromArgb(68, 88, 112);
            guna2ComboBox1.ItemHeight = 30;
            guna2ComboBox1.Location = new Point(748, 11);
            guna2ComboBox1.Name = "guna2ComboBox1";
            guna2ComboBox1.ShadowDecoration.CustomizableEdges = customizableEdges2;
            guna2ComboBox1.Size = new Size(155, 36);
            guna2ComboBox1.TabIndex = 10;
            guna2ComboBox1.SelectedIndexChanged += guna2ComboBox1_SelectedIndexChanged;
            // 
            // roundedButton4
            // 
            roundedButton4.BackColor = Color.Transparent;
            roundedButton4.BorderColor = Color.Transparent;
            roundedButton4.BorderRadius = 15;
            roundedButton4.BorderSize = 1;
            roundedButton4.FlatAppearance.BorderSize = 0;
            roundedButton4.FlatStyle = FlatStyle.Flat;
            roundedButton4.ForeColor = Color.DarkGray;
            roundedButton4.Image = (Image)resources.GetObject("roundedButton4.Image");
            roundedButton4.Location = new Point(423, 14);
            roundedButton4.Name = "roundedButton4";
            roundedButton4.Size = new Size(35, 33);
            roundedButton4.TabIndex = 9;
            roundedButton4.UseVisualStyleBackColor = false;
            // 
            // label6
            // 
            label6.AutoSize = true;
            label6.BackColor = Color.White;
            label6.Font = new Font("Segoe UI", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label6.Image = Properties.Resources.Search34;
            label6.Location = new Point(411, 14);
            label6.Name = "label6";
            label6.Size = new Size(0, 28);
            label6.TabIndex = 8;
            // 
            // roundedButton3
            // 
            roundedButton3.BackColor = Color.Gainsboro;
            roundedButton3.BorderColor = Color.Gray;
            roundedButton3.BorderRadius = 10;
            roundedButton3.BorderSize = 1;
            roundedButton3.FlatAppearance.BorderSize = 0;
            roundedButton3.FlatStyle = FlatStyle.Flat;
            roundedButton3.ForeColor = Color.DarkGray;
            roundedButton3.Image = (Image)resources.GetObject("roundedButton3.Image");
            roundedButton3.Location = new Point(480, 12);
            roundedButton3.Name = "roundedButton3";
            roundedButton3.Size = new Size(41, 37);
            roundedButton3.TabIndex = 7;
            roundedButton3.UseVisualStyleBackColor = false;
            // 
            // roundedButton2
            // 
            roundedButton2.BackColor = Color.FromArgb(76, 132, 96);
            roundedButton2.BorderColor = Color.Gray;
            roundedButton2.BorderRadius = 10;
            roundedButton2.BorderSize = 1;
            roundedButton2.FlatAppearance.BorderSize = 0;
            roundedButton2.FlatStyle = FlatStyle.Flat;
            roundedButton2.Font = new Font("Segoe UI", 10.2F, FontStyle.Bold, GraphicsUnit.Point, 0);
            roundedButton2.ForeColor = Color.White;
            roundedButton2.Location = new Point(922, 11);
            roundedButton2.Name = "roundedButton2";
            roundedButton2.Size = new Size(230, 38);
            roundedButton2.TabIndex = 6;
            roundedButton2.Text = "+ Thêm mẫu môi trường";
            roundedButton2.UseVisualStyleBackColor = false;
            // 
            // roundedTextBox1
            // 
            roundedTextBox1.BackColor = Color.White;
            roundedTextBox1.BorderColor = Color.Gray;
            roundedTextBox1.BorderFocusColor = Color.Gray;
            roundedTextBox1.BorderRadius = 10;
            roundedTextBox1.BorderSize = 2;
            roundedTextBox1.Font = new Font("Segoe UI", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            roundedTextBox1.Location = new Point(25, 12);
            roundedTextBox1.Name = "roundedTextBox1";
            roundedTextBox1.Padding = new Padding(8);
            roundedTextBox1.Size = new Size(437, 38);
            roundedTextBox1.TabIndex = 5;
            roundedTextBox1.Texts = "Tìm kiếm theo mã hợp đồng, tên khách hàng...";
            roundedTextBox1.UnderlinedStyle = false;
            // 
            // dgvSamples
            // 
            dgvSamples.AllowUserToAddRows = false;
            dgvSamples.AllowUserToDeleteRows = false;
            dataGridViewCellStyle1.BackColor = Color.White;
            dgvSamples.AlternatingRowsDefaultCellStyle = dataGridViewCellStyle1;
            dgvSamples.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            dgvSamples.BackgroundColor = Color.White;
            dgvSamples.BorderStyle = BorderStyle.None;
            dataGridViewCellStyle2.Alignment = DataGridViewContentAlignment.MiddleLeft;
            dataGridViewCellStyle2.BackColor = Color.FromArgb(76, 132, 96);
            dataGridViewCellStyle2.Font = new Font("Segoe UI", 9F, FontStyle.Bold);
            dataGridViewCellStyle2.ForeColor = Color.White;
            dataGridViewCellStyle2.SelectionBackColor = Color.FromArgb(76, 132, 96);
            dataGridViewCellStyle2.SelectionForeColor = SystemColors.HighlightText;
            dataGridViewCellStyle2.WrapMode = DataGridViewTriState.True;
            dgvSamples.ColumnHeadersDefaultCellStyle = dataGridViewCellStyle2;
            dgvSamples.ColumnHeadersHeight = 45;
            dgvSamples.Columns.AddRange(new DataGridViewColumn[] { contractCode, sampleCode, sampleType, sampleDescription, sampleLocation, createdAt, ThaoTac });
            dataGridViewCellStyle3.Alignment = DataGridViewContentAlignment.MiddleLeft;
            dataGridViewCellStyle3.BackColor = Color.White;
            dataGridViewCellStyle3.Font = new Font("Segoe UI", 9F);
            dataGridViewCellStyle3.ForeColor = Color.FromArgb(71, 69, 94);
            dataGridViewCellStyle3.SelectionBackColor = Color.FromArgb(231, 229, 255);
            dataGridViewCellStyle3.SelectionForeColor = Color.FromArgb(71, 69, 94);
            dataGridViewCellStyle3.WrapMode = DataGridViewTriState.False;
            dgvSamples.DefaultCellStyle = dataGridViewCellStyle3;
            dgvSamples.EnableHeadersVisualStyles = false;
            dgvSamples.GridColor = Color.White;
            dgvSamples.Location = new Point(25, 78);
            dgvSamples.Name = "dgvSamples";
            dgvSamples.ReadOnly = true;
            dgvSamples.RowHeadersVisible = false;
            dgvSamples.RowHeadersWidth = 51;
            dgvSamples.RowTemplate.Height = 50;
            dgvSamples.Size = new Size(1127, 533);
            dgvSamples.TabIndex = 3;
            // 
            // contractCode
            // 
            contractCode.HeaderText = "Mã hợp đồng";
            contractCode.MinimumWidth = 6;
            contractCode.Name = "contractCode";
            contractCode.ReadOnly = true;
            contractCode.Width = 125;
            // 
            // sampleCode
            // 
            sampleCode.HeaderText = "Kiểu mẫu";
            sampleCode.MinimumWidth = 6;
            sampleCode.Name = "sampleCode";
            sampleCode.ReadOnly = true;
            sampleCode.Width = 125;
            // 
            // sampleType
            // 
            sampleType.HeaderText = "Loại mẫu";
            sampleType.MinimumWidth = 6;
            sampleType.Name = "sampleType";
            sampleType.ReadOnly = true;
            sampleType.Width = 125;
            // 
            // sampleDescription
            // 
            sampleDescription.HeaderText = "Mô tả";
            sampleDescription.MinimumWidth = 6;
            sampleDescription.Name = "sampleDescription";
            sampleDescription.ReadOnly = true;
            sampleDescription.Width = 125;
            // 
            // sampleLocation
            // 
            sampleLocation.HeaderText = "Vị trí";
            sampleLocation.MinimumWidth = 6;
            sampleLocation.Name = "sampleLocation";
            sampleLocation.ReadOnly = true;
            sampleLocation.Width = 125;
            // 
            // createdAt
            // 
            createdAt.HeaderText = "Ngày tạo";
            createdAt.MinimumWidth = 6;
            createdAt.Name = "createdAt";
            createdAt.ReadOnly = true;
            createdAt.Width = 125;
            // 
            // ThaoTac
            // 
            ThaoTac.HeaderText = "Thao tác";
            ThaoTac.MinimumWidth = 6;
            ThaoTac.Name = "ThaoTac";
            ThaoTac.ReadOnly = true;
            ThaoTac.Text = "•••";
            ThaoTac.UseColumnTextForButtonValue = true;
            ThaoTac.Width = 125;
            // 
            // sampleStatus
            // 
            sampleStatus.HeaderText = "Trạng thái";
            sampleStatus.MinimumWidth = 6;
            sampleStatus.Name = "sampleStatus";
            sampleStatus.ReadOnly = true;
            sampleStatus.Width = 125;
            // 
            // userDropdownMenu
            // 
            userDropdownMenu.ImageScalingSize = new Size(20, 20);
            userDropdownMenu.Items.AddRange(new ToolStripItem[] { viewProfileItem, logoutItem });
            userDropdownMenu.Name = "userDropdownMenu";
            userDropdownMenu.Size = new Size(142, 52);
            // 
            // viewProfileItem
            // 
            viewProfileItem.ForeColor = Color.FromArgb(64, 64, 64);
            viewProfileItem.Name = "viewProfileItem";
            viewProfileItem.Size = new Size(141, 24);
            viewProfileItem.Text = "Thông tin";
            // 
            // logoutItem
            // 
            logoutItem.ForeColor = Color.FromArgb(64, 64, 64);
            logoutItem.Name = "logoutItem";
            logoutItem.Size = new Size(141, 24);
            logoutItem.Text = "Thoát";
            // 
            // pSidebar
            // 
            pSidebar.BackColor = Color.FromArgb(45, 55, 72);
            pSidebar.Controls.Add(cpbLogo);
            pSidebar.Controls.Add(roundedButton1);
            pSidebar.Controls.Add(line1);
            pSidebar.Controls.Add(label4);
            pSidebar.Controls.Add(label3);
            pSidebar.Controls.Add(label2);
            pSidebar.Controls.Add(label1);
            pSidebar.Dock = DockStyle.Left;
            pSidebar.Location = new Point(0, 0);
            pSidebar.Name = "pSidebar";
            pSidebar.Size = new Size(320, 674);
            pSidebar.TabIndex = 6;
            // 
            // cpbLogo
            // 
            cpbLogo.BackColor = Color.Transparent;
            cpbLogo.Location = new Point(11, 93);
            cpbLogo.Name = "cpbLogo";
            cpbLogo.Size = new Size(68, 68);
            cpbLogo.TabIndex = 2;
            cpbLogo.TabStop = false;
            // 
            // roundedButton1
            // 
            roundedButton1.BackColor = Color.FromArgb(45, 55, 72);
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
            roundedButton1.Click += roundedButton1_Click;
            // 
            // line1
            // 
            line1.LineColor = Color.White;
            line1.LineWidth = 1;
            line1.Location = new Point(-3, 176);
            line1.Name = "line1";
            line1.Size = new Size(323, 29);
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
            label2.Size = new Size(182, 25);
            label2.TabIndex = 1;
            label2.Text = "🏢 Mẫu môi trường";
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.BackColor = Color.Transparent;
            label1.Font = new Font("Segoe UI", 15F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label1.ForeColor = Color.White;
            label1.Location = new Point(85, 109);
            label1.Name = "label1";
            label1.Size = new Size(149, 35);
            label1.TabIndex = 1;
            label1.Text = "EMC Group";
            // 
            // fPlanning
            // 
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.White;
            ClientSize = new Size(1502, 674);
            Controls.Add(pSidebar);
            Controls.Add(panel1);
            MinimumSize = new Size(1520, 721);
            Name = "fPlanning";
            Text = "Phòng Kinh Doanh";
            Load += fPlanning_Load;
            panel1.ResumeLayout(false);
            panel1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)cpbAvatar).EndInit();
            CustomGradientPanel1.ResumeLayout(false);
            CustomGradientPanel1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)dgvSamples).EndInit();
            userDropdownMenu.ResumeLayout(false);
            pSidebar.ResumeLayout(false);
            pSidebar.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)cpbLogo).EndInit();
            ResumeLayout(false);
        }

        #region Fields
        private Panel CustomGradientPanel1;
        private TextBox Thanhtimkiem;
        private DataGridView dgvSamples;
        private DataGridViewTextBoxColumn contractCode;
        private DataGridViewTextBoxColumn sampleCode;
        private DataGridViewTextBoxColumn sampleType;
        private DataGridViewTextBoxColumn sampleDescription;
        private DataGridViewTextBoxColumn createdAt;
        private DataGridViewTextBoxColumn sampleLocation;
        private DataGridViewTextBoxColumn sampleStatus;
        private DataGridViewButtonColumn ThaoTac;
        private Panel panel1;
        private ContextMenuStrip userDropdownMenu;
        private ToolStripMenuItem viewProfileItem;
        private ToolStripMenuItem logoutItem;
        #endregion

        private Panel pSidebar;
        private Controls.CirclePictureBox cpbLogo;
        private Controls.RoundedButton roundedButton1;
        private Controls.Line line1;
        private Label label4;
        private Label label3;
        private Label label2;
        private Label label1;
        private Controls.RoundedTextBox roundedTextBox1;
        private Controls.CirclePictureBox cpbAvatar;
        private Label lFullname;
        private Controls.RoundedButton roundedButton2;
        private Label label5;
        private Controls.RoundedButton roundedButton3;
        private Controls.RoundedButton roundedButton4;
        private Label label6;
        private Guna.UI2.WinForms.Guna2AnimateWindow guna2AnimateWindow1;
        private Guna.UI2.WinForms.Guna2ComboBox guna2ComboBox1;
    }
}