from re import split
from django.http import request
from django.http.response import HttpResponse,JsonResponse
from django.shortcuts import redirect, render
from oa.models import menu,role,user
from oa.form import menuForm
import datetime
import logging
import json
from django.middleware.csrf import get_token ,rotate_token
# Create your views here.

def menus(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '菜单管理'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'menu.html',context)

#返回json数据
def menuData(request):
    data = menu.objects.filter().values()
     
    data2 = menu.objects.filter(status=1,pid=0).values()
    ls = list([x for x in data])
    ls = setData(ls)
    ls2 = list([x for x in data2])
    res = {'menu':ls,'menuSelectList':ls2}
    #return HttpResponse(json.dumps(data),content_type="application/json")
    return JsonResponse(res,safe=False)
    
def setData(data):
    statusMap = {1:'正常',2:'禁用'}
    for x in data:
        x['status_str'] = statusMap[x['status']]
        #print(x)
    rlist = []
    handleRec(data,0,rlist)
    return rlist

def handleRec(data,pid=0,rlist = []):
    
    for x in data:
        if x['pid'] == pid:
            x['label'] = x['name']
            rlist.append(x)
            x['children'] = []
            handleRec(data,x['id'],x['children'])

#前后端不分离
def add(request):
    if request.method == 'GET':
        get_token(request)
        return render(request,'menu_add.html')
    else:
        form = menuForm(request.POST)
        if form.is_valid():
            data = form.cleaned_data
            data.created_at = datetime.datetime.today()
            menu.objects.create(**data)
            return redirect('/menus/')
        else:
            clear_errors = form.errors.get("__all__")
            get_token(request)
            return render(request,"menu_add.html",{"form": form,'error':clear_errors})

#前后端不分离          
def edit(request,id):
    menu_obj = menu.objects.get(id=id)
    if request.method == 'POST':
        form = menuForm(request.POST,instance=menu_obj)
        if form.is_valid():
            data = form.cleaned_data
            data.save()
            return redirect('/menu/')
        else:
            clear_errors = form.errors.get("__all__")
            get_token(request)
            return render(request,"menu_edit.html",{"form": form,'error':clear_errors})
    else:
        form = menuForm(instance=menu_obj)
        get_token(request)
        return render(request,'menu_edit.html',{'form':form})

#前后端不分离
def save2(request):
    
    params = json.loads(request.body)
    if request.method == 'POST':
        id = params.get('id')
        if id :
            menu_obj = menu.objects.get(pk=id)
            form = menuForm(params,instance=menu_obj)
        
            if form.is_valid():
                #data = form.cleaned_data
                # form = form.save(commit=False)
                # form.idx = 1 #修改其他数据
                form.save()
                return redirect('/oa/menu/')
            else:
            
                clear_errors = form.errors.get("__all__")
                return HttpResponse(clear_errors)
        else:
            form = menuForm(params)
            # for pam in request.POST:
            #     val = request.POST[pam]
            #     logging.info(pam)
            if form.is_valid():
                data = form.cleaned_data
                #data.created_at = datetime.datetime.today()
                menu.objects.create(**data)
                return redirect('/oa/menu/')
            else:
                logging.info(form.errors)
                clear_errors = form.errors.get("__all__")
                # return render(request,"menu_add.html",{"form": form,'error':clear_errors})
                return HttpResponse(clear_errors)

#前后端分离
def save(request):
    #logging.basicConfig(level=logging.DEBUG, encoding='utf8', filename='log.txt', filemode='a',format = '%(asctime)s - %(name)s - %(levelname)s - %(message)s')
        
        
    params = json.loads(request.body)
    #print(request.method)
    #id = request.POST.get('id')
    id = params.get('id')
    if id :
        menu_obj = menu.objects.get(pk=id)
        menu_obj.name = params.get('name')
        menu_obj.url = params.get('url')
        menu_obj.pid = params.get('pid')
        menu_obj.icon = params.get('icon')
        menu_obj.status = params.get('status')
        menu_obj.idx = params.get('idx')
        menu_obj.save()
        res = {
            'code':1,
            'msg' : '修改成功'
        }
        return JsonResponse(res)
        # form = menuForm(params,instance=menu_obj)
       
        # if form.is_valid():
        #     #data = form.cleaned_data
        #     # form = form.save(commit=False)
        #     # form.idx = 1
        #     form.save()
        #     return redirect('/oa/menu/')
        # else:
           
        #     clear_errors = form.errors.get("__all__")
        #     #return render(request,"menu_edit.html",{"form": form,'error':clear_errors})
            # res = {
            #     'code':0,
            #     'msg' : clear_errors
            # }
            # return JsonResponse(res)
    else:
        form = menuForm(params)
        
        #logging.info(params)
        #logging.info(form.data)
        # for pam in request.POST:
        #     val = request.POST[pam]
        #     logging.info(pam)
        if form.is_valid():
            data = form.cleaned_data
            
            #data.created_at = datetime.datetime.today()
            menu.objects.create(**data)
            res = {
                'code':1,
                'msg' : '新增成功'
            }
            return JsonResponse(res)
        else:
            logging.info(form.errors)
            clear_errors = form.errors.get("__all__")
            # return render(request,"menu_add.html",{"form": form,'error':clear_errors})
            res = {
                'code':0,
                'msg' :'验证失败'
            }
            return JsonResponse(res)

#前后端分离
def delete(request,id):
    menu.objects.filter(id=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功'
    }
    return JsonResponse(res)

def data(request):
    data = menu.objects.all().values()
    data = list(data)
    rlist = []
    handleRec(data,0,rlist)
    return JsonResponse(rlist,safe=False)

#左侧菜单
def menuList(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    role_id = me.role_id
    role_menu_obj =role.objects.filter(pk=role_id).values().first()
    role_menu = role_menu_obj['menus'].split(',')
    menus = menu.objects.filter(status=1).values().order_by('idx')
    listMenus = []
    for x in menus:
        if str(x['id']) in role_menu:
            listMenus.append(x)
    menuData = handleMenu(listMenus,pid=0)
    return JsonResponse(menuData,safe=False)


def handleMenu(listMenus,pid=0,index_str=''):
    list1 = []
    index = 0
    for x in listMenus:
        index += 1
        if x['pid'] == pid:
            x['index'] = str(index) if index_str == '' else (index_str+'-'+str(index))
            x['child'] = handleMenu(listMenus,x['id'],x['index'])
            list1.append(x)
    return list1
    
