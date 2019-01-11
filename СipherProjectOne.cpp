#include "pch.h"
#include "windows.h"

#include <iostream>
#include <string>
#include <fstream>
#include <algorithm>

using namespace std;

char * buffer = new char[256];

void alphabet() {

	fstream alph;
	alph.open("lool.txt", ios::in);
	while (!alph.eof()) {
		alph.get(buffer, 256, '\n');
		alph >> *buffer;
	}

}

string keyCorrector(string key, string word) {
	if (word.length() > key.length()) {
		for (int i = 0; word.length() != key.length(); i++) {
			key = key + key[i];
		}
	}
	return key;
}


string coderator(string key, string word) {
	key = keyCorrector(key, word);
	int sup1 = word.length();

	for (int j = 0; j < sup1; j++) {
		int k = (int)key[j] - 48;
		int findval = string(buffer).find(word[j]) + k;
		if (findval < string(buffer).size()) {
			word[j] = buffer[findval];
		}
		else {
			word[j] = buffer[findval - string(buffer).size()];
		}
	}
	cout << "Зашифрованное слово: \t" << word <<  endl;

	for (int j = 0; j < sup1; j++) {
		int k = (int)key[j] - 48;
		int findval = string(buffer).find(word[j]) - k;
		if (findval >= 0) {
			word[j] = buffer[findval];
		}
		else {
			findval = -findval;
			word[j] = buffer[string(buffer).size() - findval];
		}
	}
	cout << "Расшифрованное слово: \t" << word <<  endl; 
	return word;
}

int main() {
	setlocale(0, "rus");
	SetConsoleOutputCP(1251);
	SetConsoleCP(1251);

	alphabet();
	string a,b;

	cout << "Введите ключ шифрования :" << endl;
	cin >> a;

	cout << "Введите шифруемое слово: " << endl;
	cin >> b;

	coderator(a, b);

	delete[] buffer;
}
