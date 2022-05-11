# Dao

## 判断是否有数据
1. `$dao = xxxDaoImpl::query()->get()`
   1. `$dao->isEmpty()`
2. `$data = xxxService()->getByCache()`
   1. `$data === null`
3. `$data = xxxDaoImpl::query()->value('xx')`
   1. `$data === null`
4. `$data = xxxDaoImpl::query()->first()`
   1. `$data === null`
