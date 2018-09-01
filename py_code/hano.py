def hano(n,a,b,c):
	'''
		汉诺塔的递归实现
		n:代表几个塔
		a:代表第一个塔
		b:代表第二个塔
		c:代表第三个塔
	'''
	if n==1:
		print(a,"-->",c)
		return None
	if n==2:
		print(a,"-->",b)
		print(a,"-->",c)
		print(b,"-->".c)
		return None
	hano(n-1,a,c,b)
	print(a,"-->",c)
	hano(n-1,b,a,c)