<%
Set objWSHNetwork = Server.CreateObject("WScript.Network")
node = objWSHNetwork.ComputerName

Set RegularExpressionObject = New RegExp
With RegularExpressionObject
.Pattern = "\d{2,4}"
.IgnoreCase = False
.Global = True
End With
Set expressionmatch = RegularExpressionObject.Execute(node)

For Each expressionmatched in expressionmatch
nodenumber = expressionmatched.Value
Next

nodeint = int(nodenumber)
nodelength = len(node)
Response.Write node & "<br />Padding: " & String(nodeint-nodelength-15, ".")

Set RegularExpressionObject = nothing
%>