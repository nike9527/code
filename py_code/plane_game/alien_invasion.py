import sys
import pygame
from setting import Settings
import game_function as gf
from pygame.sprite import Group
from pygame.sprite import Sprite
import pygame.font
class Ship(Sprite):
	"""初始化飞船并设置其初始位置"""
	def __init__(self, ai_setting, screen):
		super(Ship, self).__init__()
		# 加载飞船图像并获取其外接矩形
		self.screen = screen
		self.image = pygame.image.load(".\\images\\ship.bmp")
		self.rect = self.image.get_rect()
		self.screen_rect = screen.get_rect()
		self.ai_setting = ai_setting
		# 将每艘新飞船放在屏幕底部中央
		self.rect.centerx = self.screen_rect.centerx
		self.rect.bottom = self.screen_rect.bottom
		self.center = float(self.rect.centerx)
		self.moving_right = False
		self.moving_left = False
		self.moving_up = False
		self.moving_down = False
	def update(self):
		print(self.screen_rect)
		"""根据移动标志调整飞船位置"""
		if self.moving_right and self.rect.right < self.screen_rect.right:
			self.center += self.ai_setting.ship_speed_factor
		if self.moving_left and self.rect.left > 0:
			self.center -= self.ai_setting.ship_speed_factor
		#if self.moving_up and self.rect.top < self.screen_rect.top:
		#	self.center += self.ai_setting.ship_speed_factor
		#if self.moving_left and self.rect.left > 0:
		# 	self.center -= self.ai_setting.ship_speed_factor	
		self.rect.centerx = self.center
	def blitme(self):
		#加载飞船
		self.screen.blit(self.image,self.rect)
	def center_ship(self):
		self.center = self.screen_rect.centerx
class GameStats():
	def __init__(self,ai_setting):
		self.ai_setting = ai_setting
		self.reset_starts()
		self.game_active = False
		self.high_score = 0
	def reset_starts(self):
		self.ship_left = self.ai_setting.ship_limit
		self.score = 0
		self.level = 1
class Scoreboard(object):
	"""显示得分信息的类"""
	def __init__(self, ai_setting, screen, stats):
		"""初始化显示得分涉及的属性"""
		self.screen = screen
		self.screen_rect = screen.get_rect()
		self.ai_setting = ai_setting
		self.stats = stats
		# 显示得分信息时使用的字体设置
		self.text_color = (30, 30, 30)
		self.font = pygame.font.SysFont(None, 48)
		# 准备初始得分图像
		self.prep_score()
		self.prep_high_score()
		self.prep_level()
		self.prep_ships()
	def prep_score(self):
		rounded_score = int(round(self.stats.score, -1))
		score_str = "{:,}".format(rounded_score)
		"""将得分转换为一幅渲染的图像"""
		score_str = str(self.stats.score)
		self.score_image = self.font.render(score_str, True, self.text_color,self.ai_setting.bg_color)
	def show_score(self):
		"""在屏幕上显示得分"""
		self.screen.blit(self.score_image, self.score_rect)
		self.screen.blit(self.high_score_image, self.high_score_rect)
	def prep_score(self):
		"""Turn the score into a rendered image."""
		rounded_score = int(round(self.stats.score, -1))
		score_str = "{:,}".format(rounded_score)
		self.score_image = self.font.render(score_str, True, self.text_color,self.ai_setting.bg_color)
		# Display the score at the top right of the screen.
		self.score_rect = self.score_image.get_rect()
		self.score_rect.right = self.screen_rect.right - 20
		self.score_rect.top = 20
	def prep_high_score(self):
		"""将最高得分转换为渲染的图像"""
		high_score = int(round(self.stats.high_score, -1))
		high_score_str = "{:,}".format(high_score)
		self.high_score_image = self.font.render(high_score_str, True,
		self.text_color, self.ai_setting.bg_color)
		#将最高得分放在屏幕顶部中央
		self.high_score_rect = self.high_score_image.get_rect()
		self.high_score_rect.centerx = self.screen_rect.centerx
		self.high_score_rect.top = self.score_rect.top
	def prep_level(self):
		"""将等级转换为渲染的图像"""
		self.level_image = self.font.render(str(self.stats.level), True,
		self.text_color, self.ai_setting.bg_color)
		# 将等级放在得分下方
		self.level_rect = self.level_image.get_rect()
		self.level_rect.right = self.score_rect.right
		self.level_rect.top = self.score_rect.bottom + 10		
	def show_score(self):
		"""在屏幕上显示飞船和得分"""
		self.screen.blit(self.score_image, self.score_rect)
		self.screen.blit(self.high_score_image, self.high_score_rect)
		self.screen.blit(self.level_image, self.level_rect)
		# 绘制飞船
		self.ships.draw(self.screen)
	def prep_ships(self):
		"""显示还余下多少艘飞船"""
		self.ships = Group()
		for ship_number in range(self.stats.ship_left):
			ship = Ship(self.ai_setting, self.screen)
			ship.rect.x = 10 + ship_number * ship.rect.width
			ship.rect.y = 10
			self.ships.add(ship)
class Button(object):
	"""docstring for Button"""
	def __init__(self, ai_setting, screen, msg):
		self.ai_setting = ai_setting
		self.screen = screen
		self.screen_rect = screen.get_rect()
		self.width,self.height = 200,50
		self.button_color = (0,255,0)
		self.text_color = (255, 255, 255)
		self.font = pygame.font.SysFont(None,48)
		self.rect = pygame.Rect(0,0,self.width,self.height)
		self.rect.center = self.screen_rect.center
		self.prep_msg(msg)
	def prep_msg(self,msg):
		self.msg_image = self.font.render(msg,True,self.text_color,self.button_color)
		self.msg_image_rect = self.msg_image.get_rect()
		self.msg_image_rect.center = self.rect.center	
	def draw_button(self):
		# 绘制一个用颜色填充的按钮，再绘制文本
		self.screen.fill(self.button_color, self.rect)
		self.screen.blit(self.msg_image, self.msg_image_rect)
def run_game():
	#初始化游戏创建一个屏幕对象
	pygame.init()
	ai_setting = Settings()
	screen = pygame.display.set_mode((ai_setting.screen_width,ai_setting.screen_height))
	pygame.display.set_caption("Alien Invasion")
	stats = GameStats(ai_setting)
	#创建一个飞船
	ship = Ship(ai_setting,screen)
	bullets = Group()
	aliens = Group()
	play_button = Button(ai_setting,screen,"Play")
	gf.create_fleet(ai_setting,screen,aliens,ship)
	sb = Scoreboard(ai_setting, screen, stats)
	#开始游戏的主循环
	while True:
		#监视键盘和表鼠标事件
		gf.check_event(ai_setting, screen, ship, bullets, play_button, stats, aliens, sb)
		if stats.game_active:
			ship.update()
			gf.update_bullets(ai_setting,screen,ship,aliens,bullets,sb,stats)
			gf.update_aliens(ai_setting,aliens,ship,screen,bullets,sb,stats)
		gf.update_screen(ai_setting,screen,ship,bullets,aliens,stats,play_button,sb)
		#不停刷新屏幕
		pygame.display.flip()
run_game()