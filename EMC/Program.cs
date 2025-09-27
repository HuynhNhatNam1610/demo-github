using EMC.UI.Forms;

namespace EMC
{
    internal static class Program
    {
        /// <summary>
        ///  The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            // To customize application configuration such as set high DPI settings or default font,
            // see https://aka.ms/applicationconfiguration.
            ApplicationConfiguration.Initialize();
            //fReview f2 = new fReview();
            fLogin f1 = new fLogin();
            fReview f2 = new fReview();
            PhongKinhDoanh f5 = new PhongKinhDoanh();
            fPlanning f6 = new fPlanning();
            fAdd_EditSample f7 = new fAdd_EditSample();

            //f1.Show();
            //f6.Show();
            f6.Show();
            //f4.Show();
            //form1.Show();
            Application.Run();
        }
    }
}