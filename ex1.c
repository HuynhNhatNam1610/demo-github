#include <stdio.h>
int vonglap1(int n1,int i1,int tong1){
for(i1; i1<=n1; i1++) {
        if(i1 % 2 == 0) {
            tong1+=i1;
        }
    }
printf("tong1= %d\n", tong1);
}
int vonglap2(int n2, int j1,int tong2){

    while(j1 <= n2){
        if(j1 % 2 == 0) {
            tong2+=j1;
        } j1++;
    }
printf("tong2=%d\n", tong2);
}
int vonglap3(int n3, int k1, int tong3){
    do{
        if(k1 % 2 == 0) {
            tong3+=k1;
        } k1++;
    } while(k1<=n3);
printf("tong3=%d", tong3);
}
int main(){
    int n, i = 1,j=1,k=1, sum1=0, sum2=0, sum3=0;
    printf("Nhap n =");
    scanf("%d", &n);
    vonglap1(n,i,sum1);
    vonglap2(n,j,sum2);
    vonglap3(n,k,sum3);
return 0;
}