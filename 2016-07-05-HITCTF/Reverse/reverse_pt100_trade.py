def encode(flag):
	table = ['A', 'B', 'C', 'D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','+','/']
	result = ""
	for i in [i for i in xrange(len(flag)) if not(i%3)]:
		if i + 3 > len(flag):
			result = None
			break
			
		result += table[(ord(flag[i]) >> 2) & 0x3F]
		result += table[(((ord(flag[i]) << 6 ) >> 2) & 0x30) | ((ord(flag[i + 1]) >> 4) & 0x0F)]
		result += table[(((ord(flag[i + 1]) << 4) >> 2) & 0x3C) | ((ord(flag[i + 2]) >> 6) & 0x03)]
		result += table[ord(flag[i + 2]) & 0x3F]
	
	return result
	
if __name__ == '__main__':
	while True:
		flag = raw_input()
		if encode(flag) == 'QTVTc3NzX3czZWVlXzd0UmFkZEVl':
			print 'hitctf{' + flag + '!}'
		else:
			print "Try again!"
