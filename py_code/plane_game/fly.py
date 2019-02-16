import pygame
from pygame.locals import *
import time
import random
class Base(object):
	"""docstring for Base"""
	def __init__(self,screen,x,y,image):
		self.x = x
		self.y = y
		self.screen = screen
		self.image = pygame.image.load(image)
class BasePlane(Base):
	"""docstring for BasePlane"""
	def __init__(self,screen,x,y,image):
		super(BasePlane,self).__init__(screen,x,y,image)
		self.bullet_list = []
	def display(self):
		#装载飞机图
		self.screen.blit(self.image,(self.x,self.y))
		for bullet in self.bullet_list:
			bullet.display()
			bullet.move()
			if bullet.judget():
				self.bullet_list.remove(bullet)	
class BaseBullet(Base):
	"""docstring for BaseBulle"""
	def __init__(self,screen,x,y,image):
		super(BaseBullet,self).__init__(screen,x,y,image)
	def display(self):
		self.screen.blit(self.image,(self.x,self.y))			
class HeroPlane(BasePlane):
	"""docstring for HeroPlane"""
	def __init__(self,screen):
		#super.(HeroPlane,self).__init__(screen,200,700,".\\les\\feiji\\hero1.png")
		BasePlane.__init__(self,screen,200,700,".\\les\\feiji\\hero1.png")
	def move_left(self):
		self.x -= 5
	def move_right(self):
		self.x += 5
	def fier(self):
		self.bullet_list.append(Bullet(self.screen,self.x,self.y))
class EnemyPlane(BasePlane):
	"""docstring for HeroPlane"""
	def __init__(self,screen):
		super(EnemyPlane,self).__init__(screen,0,0,".\\les\\feiji\\enemy0.png")
		self.direction = 'right'
	def move(self):
		if self.direction == 'right':
			self.x += 2
		elif self.direction == 'left':
			self.x -= 2
		if self.x > 480-50:
			self.direction = 'left'
		elif self.x < 0:
			self.direction = 'right'
	def fire(self):
		random_num = random.randint(1,100)
		if random_num == 10 or random_num == 20:
			self.bullet_list.append(EnemyBullet(self.screen,self.x,self.y))
class Bullet(BaseBullet):
	def __init__(self,screen,x,y):
		BaseBullet.__init__(self,screen,x+40,y-20,".\\les\\feiji\\bullet.png")
	def move(self):
		self.y -= 20
	def judget(self):
		if self.y < 0:
			return True
		else:
			return False
class EnemyBullet(BaseBullet):
	def __init__(self,screen,x,y):
		BaseBullet.__init__(self,screen,x+40,y+20,".\\les\\feiji\\bullet1.png")
	def move(self):
		self.y += 5
	def judget(self):
		if self.y < 0:
			return True
		else:
			return False
def main():
	#创建窗口
	screen = pygame.display.set_mode((480,850),0,32)
	#创建一个背景图片
	background = pygame.image.load(".\\les\\feiji\\background.png")
	#创建飞机图片
	hero1 = HeroPlane(screen)
	#创建一个敌机
	enemy = EnemyPlane(screen)
	while True:
		#装载背景图
		screen.blit(background,(0,0))
		#装载飞机图
		hero1.display()
		enemy.display()
		enemy.move()
		enemy.fire()
		keyEvent(hero1)
		#渲染
		pygame.display.update()
		#time.sleep(0.01)
def keyEvent(hero):
	global x
	global y
	for event in pygame.event.get():
		if event.type == QUIT:
			print("exit")
			exit()
		elif event.type == KEYDOWN:
			if(event.key) == K_a or event.key == K_LEFT:
				print("left")
				hero.move_left()
			elif event.key == K_d or event.key == K_RIGHT:
				print("right")
				hero.move_right()
			elif event.key == K_SPACE:
				print("space")
				hero.fier()		
if __name__ =='__main__':
	main()