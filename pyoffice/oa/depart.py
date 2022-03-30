from os import error
from django.http import request
from django.http.response import JsonResponse
from django.shortcuts import redirect, render
from oa.models import depart,user
from oa.form import departForm
import json
from django.db.models import Count
from django.middleware.csrf import get_token ,rotate_token

def index(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '部门管理'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'depart.html',context)


def data(request):
    data = list(depart.objects.all().values())
    top_data = list(depart.objects.filter(pid=0).values())

    #统计人数
    userinfo = user.objects.values('depart').annotate(count=Count('depart'))
    countDict = {}
    for x in userinfo:
        if not x['depart']:
            continue
        countDict[x['depart']] = x['count']
    for d in data:
        d['count'] = countDict[d['id']] if d['id'] in countDict else 0


    #树   
    data = handledata(data,0)
    res = {
        'data':data,
        'menuSelectList':top_data
    }
    return JsonResponse(res,safe=False)

def handledata(data,pid):
    lst = []
    for val in data:
        if val['pid'] == pid:
            lst.append(val)
            val['children'] = handledata(data,val['id'])
    return lst

def save(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        id = params.get('id')
        if id :
            obj = depart.objects.get(pk=id)
            
            forms = departForm(params,instance=obj)
            if forms.is_valid():
                # forms.save()
                obj.name = params.get('name')
                obj.pid = params.get('pid')
                obj.intro = params.get('intro')
                obj.save()
                res = {
                    'code':1,
                    'msg' : '修改成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)
        else:
            forms = departForm(params)
            if forms.is_valid():
                data = forms.cleaned_data
                depart.objects.create(**data)
                res = {
                    'code':1,
                    'msg' : '新增成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)

def delete(request,id):
    depart.objects.get(pk=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功' 
    }
    return JsonResponse(res)


def option(request):
    data = list(depart.objects.all().values())
    data = handleOption(data,0)
    return JsonResponse(data,safe=False)

def handleOption(data,pid=0):
    lst = []
    for val in data:
        if val['pid'] == pid:
            val['label'] = val['name']
            val['value'] = val['id']
            if handleOption(data,val['id']):
                val['children'] = handleOption(data,val['id'])
            lst.append(val)
    return lst


    