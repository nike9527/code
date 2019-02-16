import pygame
from pygame.sprite import Group
from pygame.sprite import Sprite
class Bullet(Sprite):
	"""docstring for Bullet"""
	def __init__(self, ai_setting, screen, ship):
		"""在飞船位置创建要给子弹对象"""
		super(Bullet,self).__init__()
		self.screen = screen
		#在(0,0)创建一个表示子弹的矩形  在设置正确的位置
		self.rect = pygame.Rect(0,0,ai_setting.bullet_width,ai_setting.bullet_height)
		self.rect.centerx = ship.rect.centerx
		self.rect.top = ship.rect.top
		#存储用小数表示的子弹位置
		self.y = float(self.rect.y)
		self.color = ai_setting.bullet_color
		self.speed_factor = ai_setting.bullet_speed_factor
	def update(self):
		"""向上的子弹"""
		self.y -= self.speed_factor
		self.rect.y = self.y
	def draw_bullet(self):
		pygame.draw.rect(self.screen,self.color,self.rect)