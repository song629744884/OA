from django.http import request
from django.http.response import JsonResponse
from django.shortcuts import redirect, render
from oa.models import user,depart
from oa.form import userForm
import datetime
import json
import time
import hashlib
import random
from django.middleware.csrf import get_token ,rotate_token

def index(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '员工管理'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'user.html',context)

def data(request):
    page_num = request.GET.get('page_num')
    currentPage = request.GET.get('currentPage')
    if currentPage=='0':
        currentPage = 1
    start = (int(currentPage)-1)*int(page_num)
    end = start+int(page_num)
    data = user.objects.all().values()[start:end]
    data = list(data)

    departData = depart.objects.all().values('id','name')
    departDict = {}
    for x in departData:
        departDict[x['id']] = x['name']

    for val in data:
        val['sex_str'] = '男' if val['sex']==1 else '女'
        val['status_str'] = '正常' if val['status']==1 else '禁用'
        val['depart_str'] = departDict[val['depart']] if val['depart'] else ''
        #val['birth'] = time.strftime("%Y-%m-%d",time.localtime(val['birth']))
    total = user.objects.all().values().count()
    res = {
        'data':data,
        'total':total
    }
    return JsonResponse(res,safe=False)

def save(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        id = params.get('id')
        phonecheck = check(params.get('phone'),id)
        if not phonecheck:
            res = {
                'code':0,
                'msg' : '手机号已存在'
            }
            return JsonResponse(res)
        if id:
            obj = user.objects.get(pk=id)
            form = userForm(params,instance=obj)
            if form.is_valid():
                #form = form.save(commit=False)
                #form.birth = time.strftime('%Y-%m-%d',time.localtime(params.get('birth')))
                form.save()
                res = {
                    'code':1,
                    'msg' : '修改成功'
                }
                return JsonResponse(res)
            else:
                clear_errors = form.errors.get("__all__")
                error = list(form.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)
        else:
            form = userForm(params)
            if form.is_valid():
                data = form.cleaned_data
                # data['code'] = 'qh_'+time.strftime('%Y%m%d%H%M%S',time.localtime(time.time()))
                #print(random.sample('123456',6))
                data['code'] = 'qh_'+''.join(random.sample('123456',6))
                # 创建md5对象
                str = '123456'
                hl = hashlib.md5()
                hl.update(str.encode(encoding='utf-8'))
                data['password'] = hl.hexdigest()
                #data['birth'] = time.strftime("%Y-%m-%d",time.localtime(data['birth']))
                user.objects.create(**data)
                res = {
                    'code':1,
                    'msg' : '新增成功'
                }
                return JsonResponse(res)
            else:
                clear_errors = form.errors.get("__all__")
                error = list(form.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)

def check(phone,id=''):
    if id:
        if user.objects.filter(phone=phone).exclude(pk=id).exists():
            return False
        else:
            return True
    else:
        if user.objects.filter(phone=phone).exists():
            return False
        else:
            return True

def add(request):
    if request.method == 'POST':
        form = userForm(request.POST)
        if form.is_valid():
            data = form.cleaned_data
            data.created_at = datetime.datetime.today()
            user.objects.create(**data)
            return redirect('/user/')
        else:
            clear_errors = form.errors.get("__all__")
            get_token(request)
            return render(request,"user_add.html",{"form": form,'error':clear_errors})
    else:
        get_token(request)
        return render(request,'user_add.html')

def edit(request,id):
    user_obj = user.objects.get(id)
    if request.method == 'POST':
        form = userForm(request.POST,instance=user_obj)
        if form.is_valid():
            data = form.cleaned_data
            data.updated_at = datetime.datetime.today()
            data.save()
            return redirect('/user/')
        else:
            clear_errors = form.errors.get("__all__")
            get_token(request)
            return render(request,"user_edit.html",{"form": form,'error':clear_errors})
    else:
        form = userForm(instance=user_obj)
        get_token(request)
        return render(request,'user_edit.html',{'form':form})

def delete(request,id):
    user.objects.filter(id=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功' 
    }
    return JsonResponse(res)


def me(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '个人信息'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'me.html',context) 

def myUserInfo(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).values().first()
    res = {
        'code':1,
        'data' : me 
    }
    return JsonResponse(res,safe=False)


def saveMyUserInfo(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        phone = params.get('phone')
        name = params.get('name')
        sex = params.get('sex')
        birth = params.get('birth')
        pic = params.get('pic')
        if phone=='':
            res = {
                'code':0,
                'msg' : '手机号码有误' 
            }
            return JsonResponse(res,safe=False)
        if name=='':
            res = {
                'code':0,
                'msg' : '姓名不能为空' 
            }
            return JsonResponse(res,safe=False)
        if sex=='':
            res = {
                'code':0,
                'msg' : '请选择性别' 
            }
            return JsonResponse(res,safe=False)
        if birth=='':
            res = {
                'code':0,
                'msg' : '请选择出生日期' 
            }
            return JsonResponse(res,safe=False)
        obj = user.objects.get(phone=phone)
        obj.name = name
        obj.sex = sex
        obj.birth = birth
        obj.pic = pic
        obj.save()
        res = {
            'code':1,
            'msg' : '修改成功' 
        }
        return JsonResponse(res,safe=False)


def password(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '修改密码'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'password.html',context)

def passwordSave(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        phone = request.session.get('phone')
        password = params.get('password')
        npassword = params.get('npassword')
        rpassword = params.get('rpassword')
        if npassword!=rpassword:
            res = {
                'code':0,
                'msg' : '新密码与重复密码不一致' 
            }
            return JsonResponse(res,safe=False)
    
        hl = hashlib.md5()
        hl.update(password.encode(encoding='utf-8'))
        user_obj = user.objects.filter(phone=phone,password=hl.hexdigest()).first()
        if not user_obj:
            res = {
                'code':0,
                'msg' : '密码错误' 
            }
            return JsonResponse(res,safe=False)
        ps = hashlib.md5()
        ps.update(npassword.encode(encoding='utf-8'))
        user_obj.password = ps.hexdigest()
        user_obj.save()
        res = {
            'code':1,
            'msg' : '密码修改成功' 
        }
        return JsonResponse(res,safe=False)
        