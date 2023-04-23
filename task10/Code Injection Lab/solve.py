LETTERS="!@$%()[]_^-={}.|><,;"
text="system('cat flag.txt')"

for n in text:
    for i in LETTERS:
        for j in LETTERS:
            if ord(i) ^ ord(j) == ord(n):
                print(f"('{i}'^'{j}')={n}")
                break
            if n=='(' or n==')' or n=="'" or n==".":
                print(f"{n}={n}")
                break
        else:
            continue
        break

    

