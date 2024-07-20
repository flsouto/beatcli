from utils import *
import glob
import os
import random
uploaded = Object('data/uploaded.json')
basename = os.path.basename

globs = os.getenv('S3UPGLOB').split(';')
files = [f for g in globs for f in glob.glob(g) if basename(f) not in uploaded]
random.shuffle(files)
len = len(files)
i=1
for f in files:
	print(f'At {i} of {len}')
	publish_file(f, basename(f))
	print(file_url(basename(f)))
	uploaded[basename(f)] = 1
	uploaded.save(indent=1)
	os.system('./push.sh')
	i+=1
