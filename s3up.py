from utils import *
import glob
import os
import random

globs = os.getenv('S3UPGLOB').split(';')
files = [f for g in globs for f in glob.glob(g)]
random.shuffle(files)
print(files)

uploaded = Object('data/uploaded.json')
uploaded['test'] = 1
uploaded.save(indent=1)
