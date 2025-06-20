#include <iostream>
using namespace std;
int main() {
    cout << "How many inputs do you want to enter? ";
    cin>> numInputs;
    int numInputs;
    int sum = 0;
    for (int i = 0; i <= numInputs; i++){
        cout << "Enter a Number:";
        int input;
        cin >> input;
        sum += input;
    }
    cout << "The sum of the number is: " << sum << endl; 
    return 0;
}