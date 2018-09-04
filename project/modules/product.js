define(['jquery','knockoutjs'],function($,ko){
var viewModel=function(){
	var self = this;
	self.optionz = ko.observableArray(['NA',1,3,5,7,20]);
	self.pagesize = ko.observable(20); 
	self.thispage = ko.observable(0);
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
    if (self.pagesize() == "NA") {
        self.pagineation(self.Products().slice(0));
    } else {
        var paging = parseInt(self.pagesize(), 10),
         varone = paging * self.thispage(),
         vartwo = varone + paging;

        self.pagineation(self.Products().slice(varone, vartwo));
    }

});

}

ko.applyBindings(viewModel);
});