define(['jquery','knockoutjs'],function($,ko){
var viewModel=function(){
	var self = this;
	self.optionz = ko.observableArray(['NA',1,3,5,7,20]);
	self.mychoosepage = ko.observable(20); 
	self.countpage = ko.observable(0);
	self.pagineation = ko.observableArray();
	//JSON Data
	self.myProducts=ko.observableArray();
	$.getJSON("data.json",function(data){
		self.myProducts(data);
	});
	//JSON End Data
	//Drop Data
	self.newlist=ko.observableArray();
	self.newanotherlist=ko.observableArray();
	//Reset Dropdowns
	self.newlist.subscribe(function() {
       	self.newanotherlist(undefined);
    });
	//End Reset Dropdowns
	self.dropProducts=ko.observableArray();
	$.getJSON("dropdata.json",function(dropdata){
		self.dropProducts(dropdata);
	});

	self.subtotal = ko.pureComputed(function() {
       	return self.newlist() ? self.newlist().first:"";
    });
    self.subtota = ko.pureComputed(function() {
       	return self.newanotherlist() ? self.newanotherlist().second:"";
    });

	self.Products=ko.computed(function(){
		return self.myProducts().filter(function(place){
			if((place.name.indexOf(self.subtotal())!==-1)&&(place.name.indexOf(self.subtota())!==-1))
				return place;
		});
	});
	self.page = ko.computed(function () {
    if (self.mychoosepage() == "NA") {
        self.pagineation(self.Products().slice(0));
    } else {
        var paging = parseInt(self.mychoosepage(), 10),
         varone = paging * self.countpage(),
         vartwo = varone + paging;
        self.pagineation(self.Products().slice(varone, vartwo));
    }

});

self.allvars = function () {
    var totpages = self.Products().length / self.mychoosepage() || 1;
    return Math.ceil(totpages);
}
self.nextpage = function () { 
    if (self.countpage() < self.allvars() - 1) {
        self.countpage(this.countpage() + 1);
        self.prevproduct(true);
    }else if (self.countpage() == self.allvars() - 1){
    	self.nextproduct(false);
    }
}
self.previouspage = function () { 
    if (self.countpage() > 0) {
        self.countpage(this.countpage() - 1);
        self.nextproduct(true);
    }else if(self.countpage() == 0){
    	self.prevproduct(false);
    }
}
//shopping list
self.shopme=function(shopper){
	window.location.href="?shopimage="+shopper.image+"&shopname="+shopper.name+"&shopsku="+shopper.sku+"&shopprice="+shopper.price;
}
//end shopping list
//pagination enable
self.prevproduct = ko.observable(false);
self.nextproduct = ko.observable(true);
//end pagination enable
}

ko.applyBindings(viewModel);
});