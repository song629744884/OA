from django.http import request
from django.http.response import HttpResponse,JsonResponse
from django.shortcuts import redirect, render
from oa.models import role,user
from oa.form import roleForm
import json
from django.middleware.csrf import get_token ,rotate_token

def index(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '权限管理'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'role.html',context)

def data(request):
    data = role.objects.all().values()
    data = list(data)
    return JsonResponse(data,safe=False)

def menus(request):
    id = request.GET.get('id')
    data = role.objects.filter(pk=id).values('menus').first()
    return JsonResponse(data['menus'],safe=False)

def menuSave(request):
    params = json.loads(request.body)
    role_id = params.get('role_id')
    node = params.get('node')
    roleobj = role.objects.get(pk=role_id)
    roleobj.menus = node
    roleobj.save()
    res = {
        'code':1,
        'msg' : '修改成功'
    }
    return JsonResponse(res)

def save(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        id = params.get('id')
        if id:
            obj = role.objects.get(pk=id)
            form = roleForm(params,instance=obj)
            if form.is_valid():
                form.save()
                res = {
                    'code':1,
                    'msg' : '修改成功'
                }
                return JsonResponse(res)
            else:
                clear_errors = form.errors.get("__all__")
                res = {
                    'code':0,
                    'msg' : '修改失败'
                }
                return JsonResponse(res)
        else:
            form = roleForm(params)
            if form.is_valid():
                data = form.cleaned_data
                role.objects.create(**data)
                res = {
                    'code':1,
                    'msg' : '添加成功'
                }
                return JsonResponse(res)
            else:
                clear_errors = form.errors.get("__all__")
                res = {
                    'code':0,
                    'msg' : '添加失败'
                }
                return JsonResponse(res)
    else:
        return JsonResponse('error method')

def delete(request,id):
    role.objects.get(pk=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功'
    }
    return JsonResponse(res)
