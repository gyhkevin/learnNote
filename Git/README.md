# learngit
跟着峰哥学git

### 敲过的代码
>* `git config --global user.name "Your Name"`	配置用户
   `git config --global user.email "email@example.com"`	配置邮箱
>* `git init`	创建版本库
>* `git add <file>`	添加文件到版本库
>* `git commit -m "messages"`	提交并填写信息
>* `git status`	查看版本库状态
>* `git diff`	比较差别
>* `git log`	查看历史记录
>* `git log --pretty=oneline`	查看历史记录，以单行形式
>* `git reset --hard HEAD^`	返回到上一个版本
>* `git reset --hard <commit_id>`	返回到指定ID版本
>* `git reflog`	查看回退版本
>* `git checkout -- <file>`	撤销修改
>* `git checkout <dev>`	切换到dev分支
>* `git rm <file>`	删除文件
>* `git remote add origin git@server-name:path/repo-name.git`	关联一个远程文件库
>* `git push -u origin master`	推送master代码库
>* `git clone <url>`	从远程克隆一个项目
>* `git checkout -b <dev>`	创建并切换分支    
	相当于`git branch dev`&`git checkout dev`
>* `git branch`	查看分支
>* `git merge <dev>`	合并分支
>* `git branch -d <dev>`	删除分支
>* `git log --graph --pretty=oneline --abbrev-commit`	查看分支的合并情况
>* `git merge --no-ff -m "merge with no-ff" dev`	禁用Fast forward模式
>* `git stash`	保存工作区
>* `git stash list`	查看保存的工作区
>* `git stash apply`	恢复工作区
>* `git stash drop <id>`	删除工作区
>* `git stash pop`	恢复并删除保存的工作区
>* `git branch -D <name>`	强制丢弃一个没有被合并过的分支
>* `git tag <name>`	创建标签
>* `git tag`	查看标签
>* `git tag <tagname> <commit_id>`	指定提交打标签
>* `git show <tagname>`	查看指定标签
>* `git tag -a <tagname> -m "blablabla..."`	可以指定标签信息
>* `git tag -s <tagname> -m "blablabla..."`	可以用PGP签名标签
>* `git push origin <tagname>`	推送标签
>* `git push origin --tags`	推送全部标签
>* `git tag -d <tagname>`	删除本地标签
>* `git push origin:refs/tags/<tagname>`	远程删除标签
>* `git config --global alias.st status`	创建别名

####搭建git服务器
>* 1.`sudo apt-get install git`
>* 2.`sudo adduser git`	创建git用户
>* 3.收集所有需要登录的用户的公钥，就是他们自己的`id_rsa.pub`文件，
把所有公钥导入到`/home/git/.ssh/authorized_keys`文件里，一行一个。
>* 4.假定`/srv/sample.git`,在`/srv`目录下：`sudo git init --bare sample.git`;
>* 5.`sudo chown -R git:git sample.git`
>* 6.在`/etc/passwd`中的`git:x:1001:1001:,,,:home/git:/bin/bash`改为`git:x:1001:1001:,,,:/home/git:/usr/bin/git-shell`
>* `git clone git@server:/srv/sample.git`