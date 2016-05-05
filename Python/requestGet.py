import re
import requests
url = 'http://www.baidu.com'
header = 'User-Agent:Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36'
html = requests.get(url, headers=header);

data = {
	'entities_only':'true',
	'page':'1'
}
html_post = requests.post(url, data = data)

petton = 'color:#666666;">(.*?)</span>';
title = re.findall(petton,html.text,re.S);
for each in title:
	print(each)