<li class="heading">
    <h3 class="uppercase">News</h3>
</li>

<li class="nav-item {{ (Request::is('admin/posts*')) ? 'active open' : '' }}">
    <a href="{{ route('posts.index')}}" class="nav-link ">
        <i class="fa fa-newspaper-o" aria-hidden="true"></i> <span class="title">Posts</span>
    </a>
</li>

<li class="nav-item {{ Request::is('admin/categories*') ? 'active open' : '' }} ">
    <a href="{{ route('categories.index')}}" class="nav-link ">
        <i class="fa fa-briefcase" aria-hidden="true"></i> <span class="title">Categories</span>
    </a>
</li>

<li class="nav-item {{ Request::is('admin/tags*') ? 'active open' : '' }} ">
    <a href="{{ route('tags.index')}}" class="nav-link ">
        <i class="fa fa-tags" aria-hidden="true"></i> <span class="title">Tags</span>
    </a>
</li> 
