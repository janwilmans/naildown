
Usage: 

Edit any wordpress post or page and add:

```
[naildown-pretty]
[naildown url="https://raw.markdown.foo/master/README.md"]
```

This will render the off-site markdown seamlessly, as if it where part of the original document.
Its also possible to render only part of the markdown:

```
# The Main Title

This is the introduction

[comment]: # (naildown@section-start)

## Sub title

* option
* option

[comment]: # (naildown@section-end)

Footer text
```

This would render only the 'Sub title' section and the options, but not the Main Title nor the Footer text.

Special attention has been paid to rendering source code sections. If you add:

```
[naildown-pretty]
```
at the top of the document (once), it will include [prettify](https://github.com/google/code-prettify) in the document to syntax-highlight the code. It has language auto-detection, but you can also specify the language after the tripple backtick:

    ```cpp
         enum class Foo{};
    ```

```css
This can be added to Apperence->Customize->Customize CSS to make the code block render more compact:

pre {
	padding: 0px;
	padding-top: 5px;
	border-radius: 5px;
	font-size: 80%;
}

code.prettyprint {
	line-height: 1.2;
}

li.L0, li.L1, li.L2, li.L3, li.L4,
li.L5, li.L6, li.L7, li.L8, li.L9{
  list-style-type: decimal !important;
	color: #ababab;
}

```
