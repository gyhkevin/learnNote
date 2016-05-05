#include <stdio.h>

int main()
{
	char c = 127;
	int i = 255;
	c = c + 1;
	printf("c=%d, i=%d\n", c, i);
	return 0;
}
