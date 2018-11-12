#include "pch.h" 
#include <iostream> 
#include <iomanip> 
using namespace std; 


template <typename T> // Шаблон нашего класса Stack 

class Stack { 
private: 
T *stackPtr; // Указатель на стейка 
int size; // Размерность стейка 
T top; // Верхний элемент стейка 
public: 
Stack(int = 10); // Размер стейка по умолчанию 
~Stack(); // Деструктор стейка 
bool push(const T); // Помещение элемента в Stack 
bool pop(); // Удаление элемента из Stack 
void printStack(); 
}; 

int main() { 
Stack <int> myStack(5); 

cout « "Added elements in the Stack: "; 
int ct = 0; 
while (ct++ != 5) { 
int temp; 
cin » temp; 
myStack.push(temp); 
} 

myStack.printStack(); 

cout « "\nDelete two elements from Stack:\n"; 

myStack.pop(); 
myStack.pop(); 
myStack.printStack(); 

return 0; 
} 

template <typename T> // Конструктор 
Stack<T>::Stack(int s) { 
size = s > 0 ? s : 10; 
stackPtr = new T[size]; 
top = -1; 
} 

template <typename T> // Деструктор 
Stack<T>::~Stack() { 
delete[] stackPtr; 
} 


template <typename T> // Добавление элемента в Stack 
bool Stack<T>::push(const T value) { 
if (top == size - 1) 
return false; 
top++; 
stackPtr[top] = value; 

return true; 
} 

template <typename T> // Удаление элемента из Stack 
bool Stack<T>::pop() { 
if (top == -1) 
return false; 
stackPtr[top] = 0; 
top--; 
} 

template <typename T> // Вывод Stack на дисплей 
void Stack<T>::printStack() { 
for (int ix = size - 1; ix >= 0; ix--) 
cout « "|" « setw(4) « stackPtr[ix] « endl; 
}
