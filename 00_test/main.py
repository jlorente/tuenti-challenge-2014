#!/usr/bin/python3
import fileinput

for line in fileinput.input():
    numbers = line.split()
    total = 0
    for number in numbers:
        total += int(number)
    print(total)