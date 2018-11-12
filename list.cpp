#include "pch.h" 
#include <iostream> 
#include <iomanip> 
using namespace std; 

template <typename T> // Шаблон класса очереди 

class Line { 
private: 
T * linePtr; // Указатель на очередь 
int size; // Размерность очереди 
T top; // Последний элемент очереди 
public: 
Line(int = 10); // Размер очереди по умолчанию 
~Line(); // Деструктор очереди 
bool push(const T); // Добавление элемента в очередь 
bool pop(); // Удаление элемента из очереди 
void printLine(); 
}; 

int main() { 

setlocale(LC_ALL, "rus"); 
int ohshit; 
cout « "Введите размер очереди: \n"; 
cin » ohshit; 
Line <int> myLine(ohshit); 

cout « "Добавьте элемент в очередь:\n"; // Добавление элементов в очередь 
int ct = 0; 
while (ct++ != ohshit) { 
int temp; 
cin » temp; 
myLine.push(temp); 
} 

cout « "Список элементов в очереди: \n"; 
myLine.printLine(); 

cout « "\nУдалилось два элемента из очереди:" « endl; // Удаление двух элементов с конца очереди 
myLine.pop(); 
myLine.pop(); 
myLine.printLine(); 

return 0; 

} 

template <typename T> // Конструктор очереди 
Line<T>::Line(int s) { 
size = s > 0 ? s : 10; 
linePtr = new T[size]; 
top = -1; 
} 

template <typename T> // Деструктор очереди 
Line<T>::~Line() { 
delete[] linePtr; 
} 

template <typename T> // Добавление элемента в очередь 
bool Line<T>::push(const T value) { 
if (top == size - 1) 
return false; 
top++; 
linePtr[top] = value; 

return true; 
} 

template <typename T> // Удаление элемента из очереди 
bool Line<T>::pop() { 
if (top == -1) 
return false; 
linePtr[top-3] = 0; 
top--; 

return true; 
} 

template <typename T> // Вывод очереди на экран 
void Line<T>::printLine() { 
for(int ix = size - 1; ix >= 0; ix--) 
cout « "|" « setw(4) « linePtr[ix] « endl; 
}
