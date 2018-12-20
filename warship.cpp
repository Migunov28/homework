#include "pch.h"
#include <iostream>
#include <ctime>
#include <conio.h>

using namespace std;


void build(char arr[12][12]) {
	for (int x = 0; x < 12; x++)
	{
		for (int y = 0; y < 12; y++)
		{
			arr[x][y] = ' ';
			arr[x][0] = '#';
			arr[0][y] = '#';
			arr[11][y] = '#';
			arr[x][11] = '#';

		}
	}
}

void print(char arr[12][12]) {
	for (int x = 0; x < 12; x++)
	{
		for (int y = 0; y < 12; y++)
		{
			cout << arr[x][y] << " ";
		}
		cout << endl;
	}
}

bool generateShip(int n, char arr[12][12])
{
	int x = 1 + rand() % 9;
	int y = 1 + rand() % 9;
	int direction = rand() % 2;
	int st_l, st_r, col_l, col_r;
	st_l = x - 1;
	col_l = y - 1;
	if (!direction) {
		st_r = x + 1;
		col_r = y + n;
	}
	else {
		col_r = y + 1;
		st_r = x + n;
	}

	for (int xPos = st_l; xPos < st_r; xPos++) {
		for (int yPos = col_l; yPos < col_r; yPos++) {

				if (arr[xPos][yPos] == '*') {
					return 0;
				}
				else if (arr[xPos][yPos + 1] == '*') {
					return 0;
				}
				else if (arr[xPos + 1][yPos] == '*') {
					return 0;
				}
				else if (arr[xPos][yPos + 1] == '#') {
					return 0;
				}
			}
	}

	for (int k = 0; k < n; k++) {
				if (!direction) {
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
	while (1) {
		system("cls");
		build(arr);
		for (int i = 4; i > 0; i--) {
			for (int j = 0; j < 5 - i; j++)
			{
				generateShip(i, arr);
			}

		}
		print(arr);
		_getch();
	}

	return 0;
}
