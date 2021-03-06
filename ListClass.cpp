#include "pch.h"
#include <iostream>
#include <string>
using namespace std;

struct Node {

	int field;
	Node *next, *before;

};

class List {

	Node *header, *footer;
	int count;

	public:		List();
				~List();
				
				void delList();

				void del(int pos = 0);

				void addHeader(int n);

				void printList();

};

List::List() {

			header = footer = NULL;
			count = 0;

}

List::~List() {
	delList();
}

void List::addHeader(int n) {

			Node * elem = new Node;

			elem->before = 0;
			elem->field = n;
			elem->next = header;

			if (header != 0) {
				header->before = elem;
			}

			if (count != 0) {
				header = footer = elem;
			} else {
				header = elem;
			}

			count++;
}

void List::del(int pos) {

			if (pos == 0) {
				cout << "Input position: ";
				cin >> pos;
			}

			if(pos < 1 || pos > count) {
				cout << "Incorrect position!!!\n\n";
				return;
			}

			int i = 1;

			Node * del = header;

			while (i < pos) {
				del = del->next;
				i++;
			}

			Node * beforeDel = del->before;
			Node * nextDel = del->next;

			if (beforeDel != 0 && count != 1) {
				beforeDel->next = nextDel;
			}
			if (nextDel != 0 && count != 1) {
				nextDel->before = beforeDel;
			}
			if (pos == 1) {
				header = nextDel;
			}
			if (pos == count) {
				footer = beforeDel;
			}

			delete del;

			count--;

}

void List::delList() {

			while (count != 0) {
				del(1);
			}

}

void List::printList() {

			if (count != 0) {
			Node * elem = header;
			cout << "( ";
			while (elem->next != 0) {
				cout << elem->field << ", ";
				elem = elem->next;
			}

			cout << elem->field << ")\n";
			}

}


void main() {

	List L;

	const int n = 10;
	int choise;
	int input;
	int a[n] = { 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 };
	
	while (true) {
		cout << "Choose function above List:\n[1]-Add Element [2]-Print List [3] - Delete Element from List [4] - Delete List\n";
		cin >> choise;

		switch (choise) {
		case 1: {
			cout << "Input element in the List: ";
			cin >> input;
			L.addHeader(input);
			cout << endl;
		} 
				break;
		case 2: {
			cout << "Your List:\n";
			L.printList();
			cout << endl;
		}
				break;
		case 3: {
			cout << "Which element are you going to delete? :";
			cin >> input;
			L.del(input);
			cout << endl;
		}
				break;
		case 4: {
			cout << "Your List will have been to delete T_T .";
			L.~List();
			cout << endl;
		}
				break;
		default: exit(228);
		}
	}
}
