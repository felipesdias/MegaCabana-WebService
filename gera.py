import random
import string
from random import randint

def randomword(length):
   letters = string.ascii_lowercase
   return ''.join(random.choice(letters) for i in range(length))

for x in range(50):
  print("INSERT INTO `jogada` (id, jogo_id, nome, numero, _0, _1, _2, _3, _4, _5, _6, _7, _8, _9) VALUES(NULL, '1', ", end="")
  print("'", randomword(randint(5,15)), "', ", x,  end="", sep="")
  for y in range(10):
    print(", ", randint(1,60), sep="", end="")
  print(");")