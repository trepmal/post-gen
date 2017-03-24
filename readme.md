Post Gen
========

Post Generator. Random lorem ipsum title/content. Intended for use with [Image Gen](https://github.com/trepmal/image-gen/) (though not required).

```
wp post-gen create <number>
```

Includes text files with lorem ipsum for randomizing title and content. Image is generated, set as featured image, and attached to post. Image generated with post title.

`create` is currently the only subcommand available, there are 4 options it always accepts. If Image Gen is available, you'll get *most* of those options too (numbers will work, text will not do as expected, will fix after sleeeeeeeeeeep).

```
SYNOPSIS

  wp post-gen create <count> [--post_type=<type>] [--post_status=<status>]
[--paragraphs=<paragraphs>] [--noimage]
[--days-offset=<days-offset>] [--hours-offset=<hours-offset>]
[--img-width=<img-width>] [--img-height=<img-height>]
[--img-lowgrey=<img-lowgrey>] [--img-highgrey=<img-highgrey>]
[--img-alpha=<img-alpha>] [--img-blurintensity=<img-blurintensity>]
[--img-filename=<img-filename>] [--img-text=<img-text>]
[--img-linespacing=<img-linespacing>] [--img-textsize=<img-textsize>]
[--img-font=<img-font>] [--img-fontcolor=<img-fontcolor>]

If Image-Gen (https://github.com/trepmal/image-gen) is installed,
all `wp image-gen create` options are accepted when prefixed with 'img-'

OPTIONS

  <count>
    Number of posts

  [--post_type=<type>]
    The type of the generated posts, default post

  [--post_status=<status>]
    The status of the generated posts, default publish

  [--paragraphs=<paragraphs>]
    Single number or range, default 4,7

  [--noimage]
    No image

  [--days-offset=<days-offset>]
    Number of days in past to offset new posts or range, default 1,300,5

  [--hours-offset=<hours-offset>]
    Number of hours in past to offset new posts or range, default 1,24

  [--img-width=<img-width>]
    Width for the image in pixels, default 1000

  [--img-height=<img-height>]
    Height for the image in pixels, default 800

  [--img-lowgrey=<img-lowgrey>]
    Lower grey value (0-255), default 150

  [--img-highgrey=<img-highgrey>]
    Higher grey value (0-255), default 150

  [--img-alpha=<img-alpha>]
    Alpha transparancy (0-127), default 0

  [--img-blurintensity=<img-blurintensity>]
    How often to apply the blur effect, default 2

  [--img-filename=<img-filename>]
    old value

  [--img-text=<img-text>]
    Text to place on the image, default generated post title

  [--img-linespacing=<img-linespacing>]
    Linespacing in pixels, default 10

  [--img-textsize=<img-textsize>]
    Text size in pixels, default 40

  [--img-font=<img-font>]
    Path to font true type file, default
    {plugin-path}/fonts/SourceSansPro-BoldIt.otf

  [--img-fontcolor=<img-fontcolor>]
    Font color. Either RGB as an array or a hexcode string, default array(0,
    80, 80),

EXAMPLES

    wp post-gen create 10 --img-lowgrey=20,140 --img-highgrey=160,220 --img-width=1300

```