<style>
body {
    margin:0;
    padding:0;
    height: 300px;
    width: 300px;
}

#yaxisbuttons {
    padding:0px 0px 0px 0px;
    text-align: center;
    margin:170px 0 0 0;
    width: 160px;
    height:160px;
    background:#FF931E;
    z-index:15;
    -moz-transform:rotate(-45deg);
    -ms-transform:rotate(-45deg);
    -o-transform:rotate(-45deg);
    -webkit-transform:rotate(-45deg);
    transform-origin:left top;
}

.one{
	position:absolute;
	left:0;
	top:0;
}

.two{
	position:absolute;
	left:222px;
	top:0;
}

.three{
	position:absolute;
	left:111px;
	top:111px;
	background:#F00 !important;
}
#yaxisbuttons p {
    color:#fff;
    display:inline-block;
    line-height:111px;
    -moz-transform:rotate(45deg);
    -ms-transform:rotate(45deg);
    -o-transform:rotate(45deg);
    -webkit-transform:rotate(45deg);
    transform-origin:left top;
	text-align:center
}
</style>

<body>
<div style="position:relative">
    <div id="yaxisbuttons" class="one">
        <p>Y Button 1</p>
        <p>Y Button 2</p>
    </div>
    <div id="yaxisbuttons" class="two">
        <p>Y Button 1</p>
        <p>Y Button 2</p>
    </div>
    <div id="yaxisbuttons" class="three">
        <p>Y Button 1</p>
        <p>Y Button 2</p>
    </div>
</div>
</body>

	