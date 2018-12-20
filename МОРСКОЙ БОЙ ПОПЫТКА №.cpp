﻿#include "pch.h"
#include <iostream>
#include <ctime>
#include <conio.h>
using namespace std;


void build(char arr[12][12]) {
	for (int i = 0; i < 12; i++)
	{
		for (int j = 0; j < 12; j++)
		{
			arr[i][j] = ' ';
			arr[i][0] = '#';
			arr[0][j] = '#';
			arr[11][j] = '#';
			arr[i][11] = '#';

		}
	}
}

void print(char arr[12][12]) {
	for (int i = 0; i < 12; i++)
	{
		for (int j = 0; j < 12; j++)
		{
			cout << arr[i][j] << " ";
		}
		cout << endl;
	}
}

bool fill(int n, char arr[12][12])
{
	int x = 1 + rand() % 9;
	int y = 1 + rand() % 9;
	int napr = rand() % 2;
	int st_l, st_r, col_l, col_r;
	st_l = x - 1;
	col_l = y - 1;
	if (!napr) {
		st_r = x + 1;
		col_r = y + n;
	}
	else {
		col_r = y + 1;
		st_r = x + n;
	}

	for (int m = st_l; m < st_r; m++) {
		for (int z = col_l; z < col_r; z++) {
			if (arr[m][z] == '*') {
				return 0;
			}
		}
	}
	
	for (int k = 0; k < n; k++) {
		if (!napr) {
			arr[x][y + k] = '*';
		}
		else {
			arr[x + k][y] = '*';
		}
	}

	

	

	return 1;
}
	



int main()
{
	srand(time(NULL));
	char arr[12][12];
	while(1) {
		system("cls");
		build(arr);
		for (int i = 4; i > 0; i--) {
			for (int j = 0; j <5-i; j++)
			{
				fill(i, arr);
			}
			
		}
		print(arr);
		_getch();
	}

	return 0;
}

