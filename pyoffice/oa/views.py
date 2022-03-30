from django.http import request
from django.http.response import JsonResponse
from django.shortcuts import redirect, render
from oa.models import user
import datetime
import json
import hashlib
from django.views.decorators.csrf import csrf_exempt
from django.middleware.csrf import get_token ,rotate_token
# Create your views here.


def index(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '首页'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    # request.META["Cross-Origin-Opener-Policy"] = 'same-site'
    get_token(request)
    return render(request,'index.html',context)

def login(request):
    # request.META["CSRF_COOKIE_USED"] = True
    # request.META["Cross-Origin-Opener-Policy"] = 'same-site'
    get_token(request)
    return render(request,'login.html')

@csrf_exempt
def loginIn(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        phone = params.get('phone')
        password = params.get('password')
        hl = hashlib.md5()
        hl.update(password.encode(encoding='utf-8'))
        passwordMd5 = hl.hexdigest()
        userinfo = user.objects.filter(phone=phone,password=passwordMd5).first()
        
        if userinfo:
            request.session["phone"] = userinfo.phone
            res = {
                'code':1,
                'msg':'登录成功'
            }
            return JsonResponse(res)
        else:
            res = {
                'code':0,
                'msg':'账号密码错误'
            }
            return JsonResponse(res)

def loginOut(request):
    del request.session["phone"]
    res = {
        'code':1,
        'msg':'登出成功'
    }
    return JsonResponse(res)

def imgUpload(request):
    #for file in request.FILES['file']:
    
    
    filename = request.FILES['file'].name
    handle_uploaded_file(request.FILES['file'])
    filepath = '/static/upload/img/'+filename
    return JsonResponse(filepath,safe=False)

def handle_uploaded_file(f):
    filename = f.name
    with open('statics/upload/img/'+filename, 'wb+') as destination:
        for chunk in f.chunks():
            destination.write(chunk)
