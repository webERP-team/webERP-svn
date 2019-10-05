var CounterSales =  {

	itemlist: Array(),
	SetItemList: function(val)
	{
		this.itemlist = val;
	},

	quickentrytableid: "",
	SetQuickEntryTableId: function(val)
	{
		this.quickentrytableid = val;
	},

	quickentryrowid: "",
	SetTotalQuickEntryRowsId: function(val)
	{
		this.quickentryrowid = val;
	},

	rowcounter: 0,
	SetRowCounter: function(val)
	{
		this.rowcounter = val;
	},

	IncreaseRowCounter: function()
	{
		this.rowcounter++;
	},

	defaultdeliverydate: "",
	SetDefaultDeliveryDate: function(val)
	{
		this.defaultdeliverydate = val;
	},

	AddQuickEntry: function(itemcode)
	{
		// prevent form submitting
		event.preventDefault();
		var itemlist = this.itemlist;

		if(itemlist.includes(itemcode.value.trim()))
		{
			var table = document.getElementById(this.quickentrytableid);

			// loop quick entry table rows
			for (var j = 0, row; row = table.rows[j]; j++)
			{
				found = false;
				col = row.cells[0];
				var input_itemcode = col.firstElementChild;
				if(input_itemcode)
				{
					// check if item already in list or fill in the first empty row
					if(input_itemcode.value == '' || input_itemcode.value == itemcode.value.trim())
					{
						// set item code
						input_itemcode.value = itemcode.value.trim();
						// set qty
						row.cells[1].firstElementChild.value = row.cells[1].firstElementChild.value ? parseInt(row.cells[1].firstElementChild.value) + 1 : '1';
						found = true;
						break;
					}
				}
			}

			// when no rows matched and no more empty rows
			if(!found)
			{
				this.AddQuickEntryRow(itemcode)
			}
		}
		else
		{
			alert("item code not found!");
		}

		itemcode.value = "";
	},

	AddQuickEntryRow: function (itemcode)
	{
		var table = document.getElementById(this.quickentrytableid);
		var row = table.insertRow(-1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);

		cell1.innerHTML = "<input type='text' name='part_" + this.rowcounter + "' data-type='no-illegal-chars' title='Enter a part code to be sold. Part codes can contain any alpha-numeric characters underscore or hyphen.' size='21' maxlength='20' value='" + itemcode.value.trim() + "' />";
		cell2.innerHTML = "<input type='text' class='number' name='qty_" + this.rowcounter + "' size='6' maxlength='6' value='1' /><input type='hidden' class='date' name='ItemDue_" + this.rowcounter + " value='" + this.defaultdeliverydate + "' />";

		totalquickentry = document.getElementById(this.quickentryrowid);
		totalquickentry.value = parseInt(totalquickentry.value) + 1;

		this.IncreaseRowCounter();
	},


	totaldue: 0,
	SetTotalDue: function(val)
	{
		this.totaldue = val;
	},

	decimal: 2,
	SetDecimal: function(val)
	{
		this.decimal = val;
	},

	cashreceivedid: "",
	SetCashReceivedId: function(val)
	{
		this.cashreceivedid = val;
	},

	amountpaidid: "",
	SetAmountPaidId: function(val)
	{
		this.amountpaidid = val;
	},

	changedueid: "",
	SetChangeDueId: function(val)
	{
		this.changedueid = val;
	},

	CalculateChangeDue: function ()
	{
		received_amount = document.getElementById(this.cashreceivedid);
		paid_amount = document.getElementById(this.amountpaidid);
		change_due = document.getElementById(this.changedueid);

		if(received_amount.value >= this.totaldue)
		{
			paid_amount.value = Number(this.totaldue).toFixed(this.decimal);
			change_due.value = Number(received_amount.value - this.totaldue).toFixed(this.decimal);
		}
		else
		{
			paid_amount.value = 0;
			change_due.value = 0;
		}
	}
}