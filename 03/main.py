#!/usr/bin/python3
import math
import sys

#The solution is the hypotenuse of the two given numbers
i = 0
for line in sys.stdin:
    if i != 0:
        numbers = line.split()
        total = 0
        for number in numbers:
            total += int(number) ** 2
        print((str(round(math.sqrt(total), 2))))
    i += 1