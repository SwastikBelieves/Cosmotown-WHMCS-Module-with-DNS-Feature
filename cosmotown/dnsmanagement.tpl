{if $saveSuccess}
    <div class="alert alert-success">
        {$saveSuccess}
    </div>
{/if}

{if $errorMessage}
    <div class="alert alert-danger">
        {$errorMessage}
    </div>
{/if}

<p>Here you can manage the DNS records for your domain. Use "@" for the root domain. Changes can take up to 24 hours to propagate.</p>

<form method="post" action="{$smarty.server.REQUEST_URI}">
    <input type="hidden" name="token" value="{$token}" />

    <div class="table-responsive">
        <table id="dnsRecordsTable" class="table table-striped">
            <thead>
                <tr>
                    <th style="width:25%;">Hostname</th>
                    <th style="width:15%;">Type</th>
                    <th style="width:40%;">Value / Address</th>
                    <th style="width:10%;">Priority</th>
                    <th style="width:10%;"></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$dnsrecords item=record key=num}
                    <tr>
                        <td><input type="text" name="hostname[]" value="{$record.hostname}" class="form-control" /></td>
                        <td>
                            <select name="type[]" class="form-control" onchange="togglePriority(this)">
                                <option value="A" {if $record.type == 'A'}selected{/if}>A</option>
                                <option value="AAAA" {if $record.type == 'AAAA'}selected{/if}>AAAA</option>
                                <option value="CNAME" {if $record.type == 'CNAME'}selected{/if}>CNAME</option>
                                <option value="MX" {if $record.type == 'MX'}selected{/if}>MX</option>
                                <option value="TXT" {if $record.type == 'TXT'}selected{/if}>TXT</option>
                            </select>
                        </td>
                        <td><input type="text" name="value[]" value="{$record.address}" class="form-control" /></td>
                        <td><input type="text" name="priority[]" value="{$record.priority}" class="form-control" {if $record.type != 'MX'}disabled{/if} /></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-default" onclick="addRecord()">
        <i class="fas fa-plus"></i> Add New Record
    </button>

    <div class="form-group text-center" style="margin-top: 20px;">
        <input type="submit" value="Save Changes" class="btn btn-primary" />
    </div>
</form>

<script>
function addRecord() {
    var table = document.getElementById('dnsRecordsTable').getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(table.rows.length);
    var cells = `
        <td><input type="text" name="hostname[]" class="form-control" placeholder="@" /></td>
        <td>
            <select name="type[]" class="form-control" onchange="togglePriority(this)">
                <option value="A" selected>A</option>
                <option value="AAAA">AAAA</option>
                <option value="CNAME">CNAME</option>
                <option value="MX">MX</option>
                <option value="TXT">TXT</option>
            </select>
        </td>
        <td><input type="text" name="value[]" class="form-control" placeholder="192.168.1.1" /></td>
        <td><input type="text" name="priority[]" class="form-control" placeholder="10" disabled /></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button></td>
    `;
    newRow.innerHTML = cells;
}

function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function togglePriority(selectElement) {
    var row = selectElement.parentNode.parentNode;
    var priorityInput = row.querySelector('input[name="priority[]"]');
    if (selectElement.value === 'MX') {
        priorityInput.disabled = false;
    } else {
        priorityInput.disabled = true;
        priorityInput.value = '';
    }
}
</script>