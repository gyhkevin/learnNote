import re
import requests

data = {
	'entities_only':'true',
	'page':'1'
}
html_post = requests.post(url, data = data)
title = re.findall(petton,html_post.text,re.S);
for each in title:
	print(each)