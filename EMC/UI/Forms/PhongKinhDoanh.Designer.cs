namespace EMC.UI.Forms
{
    partial class PhongKinhDoanh
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(PhongKinhDoanh));
            DataGridViewCellStyle dataGridViewCellStyle1 = new DataGridViewCellStyle();
            DataGridViewCellStyle dataGridViewCellStyle2 = new DataGridViewCellStyle();
            DataGridViewCellStyle dataGridViewCellStyle3 = new DataGridViewCellStyle();
            panel1 = new Panel();
            label5 = new Label();
            cpbAvatar = new EMC.UI.Controls.CirclePictureBox();
            lFullname = new Label();
            CustomGradientPanel1 = new Panel();
            roundedButton4 = new EMC.UI.Controls.RoundedButton();
            label6 = new Label();
            roundedButton3 = new EMC.UI.Controls.RoundedButton();
            roundedButton2 = new EMC.UI.Controls.RoundedButton();
            roundedTextBox1 = new EMC.UI.Controls.RoundedTextBox();
            dgvCustomers = new DataGridView();
            MaHopDong = new DataGridViewTextBoxColumn();
            TenKhachHang = new DataGridViewTextBoxColumn();
            Phone = new DataGridViewTextBoxColumn();
            Email = new DataGridViewTextBoxColumn();
            NgayKy = new DataGridViewTextBoxColumn();
            TrangThai = new DataGridViewTextBoxColumn();
            NgayGiaHan = new DataGridViewTextBoxColumn();
            HanHopDong = new DataGridViewTextBoxColumn();
            ThaoTac = new DataGridViewButtonColumn();
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
            panel1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)cpbAvatar).BeginInit();
            CustomGradientPanel1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)dgvCustomers).BeginInit();
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
            label5.Location = new Point(329, 11);
            label5.Name = "label5";
            label5.Size = new Size(207, 31);
            label5.TabIndex = 5;
            label5.Text = "Quản lý hợp đồng";
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
            CustomGradientPanel1.Controls.Add(roundedButton4);
            CustomGradientPanel1.Controls.Add(label6);
            CustomGradientPanel1.Controls.Add(roundedButton3);
            CustomGradientPanel1.Controls.Add(roundedButton2);
            CustomGradientPanel1.Controls.Add(roundedTextBox1);
            CustomGradientPanel1.Controls.Add(dgvCustomers);
            CustomGradientPanel1.Location = new Point(321, 48);
            CustomGradientPanel1.Name = "CustomGradientPanel1";
            CustomGradientPanel1.Size = new Size(1181, 620);
            CustomGradientPanel1.TabIndex = 1;
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
            roundedButton2.Location = new Point(967, 11);
            roundedButton2.Name = "roundedButton2";
            roundedButton2.Size = new Size(185, 38);
            roundedButton2.TabIndex = 6;
            roundedButton2.Text = "+ Thêm hợp đồng";
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
            // dgvCustomers
            // 
            dgvCustomers.AllowUserToAddRows = false;
            dgvCustomers.AllowUserToDeleteRows = false;
            dataGridViewCellStyle1.BackColor = Color.White;
            dgvCustomers.AlternatingRowsDefaultCellStyle = dataGridViewCellStyle1;
            dgvCustomers.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            dgvCustomers.BackgroundColor = Color.White;
            dgvCustomers.BorderStyle = BorderStyle.None;
            dataGridViewCellStyle2.Alignment = DataGridViewContentAlignment.MiddleLeft;
            dataGridViewCellStyle2.BackColor = Color.FromArgb(76, 132, 96);
            dataGridViewCellStyle2.Font = new Font("Segoe UI", 9F, FontStyle.Bold);
            dataGridViewCellStyle2.ForeColor = Color.White;
            dataGridViewCellStyle2.SelectionBackColor = Color.FromArgb(76, 132, 96);
            dataGridViewCellStyle2.SelectionForeColor = SystemColors.HighlightText;
            dataGridViewCellStyle2.WrapMode = DataGridViewTriState.True;
            dgvCustomers.ColumnHeadersDefaultCellStyle = dataGridViewCellStyle2;
            dgvCustomers.ColumnHeadersHeight = 45;
            dgvCustomers.Columns.AddRange(new DataGridViewColumn[] { MaHopDong, TenKhachHang, Phone, Email, NgayKy, TrangThai, NgayGiaHan, HanHopDong, ThaoTac });
            dataGridViewCellStyle3.Alignment = DataGridViewContentAlignment.MiddleLeft;
            dataGridViewCellStyle3.BackColor = Color.White;
            dataGridViewCellStyle3.Font = new Font("Segoe UI", 9F);
            dataGridViewCellStyle3.ForeColor = Color.FromArgb(71, 69, 94);
            dataGridViewCellStyle3.SelectionBackColor = Color.FromArgb(231, 229, 255);
            dataGridViewCellStyle3.SelectionForeColor = Color.FromArgb(71, 69, 94);
            dataGridViewCellStyle3.WrapMode = DataGridViewTriState.False;
            dgvCustomers.DefaultCellStyle = dataGridViewCellStyle3;
            dgvCustomers.EnableHeadersVisualStyles = false;
            dgvCustomers.GridColor = Color.White;
            dgvCustomers.Location = new Point(25, 78);
            dgvCustomers.Name = "dgvCustomers";
            dgvCustomers.ReadOnly = true;
            dgvCustomers.RowHeadersVisible = false;
            dgvCustomers.RowHeadersWidth = 51;
            dgvCustomers.RowTemplate.Height = 50;
            dgvCustomers.Size = new Size(1127, 533);
            dgvCustomers.TabIndex = 3;
            // 
            // MaHopDong
            // 
            MaHopDong.HeaderText = "Mã hợp đồng";
            MaHopDong.MinimumWidth = 6;
            MaHopDong.Name = "MaHopDong";
            MaHopDong.ReadOnly = true;
            MaHopDong.Width = 125;
            // 
            // TenKhachHang
            // 
            TenKhachHang.HeaderText = "Tên khách hàng";
            TenKhachHang.MinimumWidth = 6;
            TenKhachHang.Name = "TenKhachHang";
            TenKhachHang.ReadOnly = true;
            TenKhachHang.Width = 125;
            // 
            // Phone
            // 
            Phone.HeaderText = "Phone";
            Phone.MinimumWidth = 6;
            Phone.Name = "Phone";
            Phone.ReadOnly = true;
            Phone.Width = 125;
            // 
            // Email
            // 
            Email.HeaderText = "Email";
            Email.MinimumWidth = 6;
            Email.Name = "Email";
            Email.ReadOnly = true;
            Email.Width = 125;
            // 
            // NgayKy
            // 
            NgayKy.HeaderText = "Ngày ký";
            NgayKy.MinimumWidth = 6;
            NgayKy.Name = "NgayKy";
            NgayKy.ReadOnly = true;
            NgayKy.Width = 125;
            // 
            // TrangThai
            // 
            TrangThai.HeaderText = "Trạng thái";
            TrangThai.MinimumWidth = 6;
            TrangThai.Name = "TrangThai";
            TrangThai.ReadOnly = true;
            TrangThai.Width = 125;
            // 
            // NgayGiaHan
            // 
            NgayGiaHan.HeaderText = "Ngày gia hạn";
            NgayGiaHan.MinimumWidth = 6;
            NgayGiaHan.Name = "NgayGiaHan";
            NgayGiaHan.ReadOnly = true;
            NgayGiaHan.Width = 125;
            // 
            // HanHopDong
            // 
            HanHopDong.HeaderText = "Hạn hợp đồng";
            HanHopDong.MinimumWidth = 6;
            HanHopDong.Name = "HanHopDong";
            HanHopDong.ReadOnly = true;
            HanHopDong.Width = 125;
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
            viewProfileItem.Click += viewProfileItem_Click;
            // 
            // logoutItem
            // 
            logoutItem.ForeColor = Color.FromArgb(64, 64, 64);
            logoutItem.Name = "logoutItem";
            logoutItem.Size = new Size(141, 24);
            logoutItem.Text = "Thoát";
            logoutItem.Click += logoutItem_Click;
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
            label2.Size = new Size(215, 25);
            label2.TabIndex = 1;
            label2.Text = "🏢 Hồ sơ doanh nghiệp";
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
            // PhongKinhDoanh
            // 
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.White;
            ClientSize = new Size(1502, 674);
            Controls.Add(pSidebar);
            Controls.Add(panel1);
            Name = "PhongKinhDoanh";
            Text = "Phòng Kinh Doanh";
            Load += PhongKinhDoanh_Load;
            panel1.ResumeLayout(false);
            panel1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)cpbAvatar).EndInit();
            CustomGradientPanel1.ResumeLayout(false);
            CustomGradientPanel1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)dgvCustomers).EndInit();
            userDropdownMenu.ResumeLayout(false);
            pSidebar.ResumeLayout(false);
            pSidebar.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)cpbLogo).EndInit();
            ResumeLayout(false);
        }

        #region Fields
        private Panel CustomGradientPanel1;
        private TextBox Thanhtimkiem;
        private DataGridView dgvCustomers;
        private DataGridViewTextBoxColumn MaHopDong;
        private DataGridViewTextBoxColumn TenKhachHang;
        private DataGridViewTextBoxColumn Phone;
        private DataGridViewTextBoxColumn Email;
        private DataGridViewTextBoxColumn NgayKy;
        private DataGridViewTextBoxColumn TrangThai;
        private DataGridViewTextBoxColumn NgayGiaHan;
        private DataGridViewTextBoxColumn HanHopDong;
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
    }
}