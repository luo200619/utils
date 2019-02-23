# 思智捷管理系统工具类
## 思智捷管理系统文章模块(版本要求2.3+)
### Arcitle类
	文章搜索
	seachArcitle($keywords = '',$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  keywords | string  |  '' | 文章标题关键字  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p | int  |  1 | 当前页  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条数  |

	默认分页样式搜索
	seachDefaultArcitle($keywords = '',$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  keywords | string  |  '' | 文章标题关键字  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条数  |

	获取分类下的所有文章列表
	getCateArcitleList($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;p | int  |  1 | 当前页  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条件  |

	获取分类下的所有文章列表(自带思智捷管理系统分页功能)
	getDefaultCateArcitleList($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pagelist | int  |  15 | 每页显示条件  |

	获取文章分类的是单页的文章详情
	getSingleCidArcitle($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章详情
	getArcitleInfo($id = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  id | int  |  0 | 文章id  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取所有需要首页显示的文章
	getHomeAllArcitle($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order,id desc | 排序  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,10] | 获取条数  |

	获取当前文章的一篇和下一篇
	getArcitleNextPrev($id = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  id | int  |  0 | 文章id  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

### ArcitleCategory类

	获取当前文章分类的所有父节点列表
	getRecursionParentCategory($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	递归获取所有的文章分类下的某分类下所有子节点
	getRecursionChildCategory($pid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  pid | int  |  0 | 父级分类ID  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章分类单个分类的详情
	getCategoryInfo($cid = 0,$options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  cid | int  |  0 | 文章分类cid  |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |

	获取文章所有分类列表
	getCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  空 | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取所有导航文章分类
	getNavCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['is_nav'=>1,'is_show'=>1] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取文章分类是单页的分类列表
	getSingleCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['cate_type'=>1] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |

	获取文章分类是列表的分类列表
	getListCategory($options = [])
	
|  参数名称 |  类型 | 默认值  | 描述  |
| ------------ | ------------ | ------------ | ------------ |
|  options | array  |  空 | 其它参数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;where | array  |  ['cate_type'=>0] | 其它条件  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;field | string  |  true | 需要的字段，默认所有字段  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limit | mixed  |  [0,15] | 获取条数  |
|  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order | string  |  sort_order| 排序  |
