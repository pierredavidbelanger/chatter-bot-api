
Imports ChatterBotAPI

'    ChatterBotAPI
'    Copyright (C) 2011 pierredavidbelanger@gmail.com
' 
'    This program is free software: you can redistribute it and/or modify
'    it under the terms of the GNU Lesser General Public License as published by
'    the Free Software Foundation, either version 3 of the License, or
'    (at your option) any later version.
'
'    This program is distributed in the hope that it will be useful,
'    but WITHOUT ANY WARRANTY; without even the implied warranty of
'    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
'    GNU Lesser General Public License for more details.
'
'    You should have received a copy of the GNU Lesser General Public License
'    along with this program.  If not, see <http://www.gnu.org/licenses/>.
Public Class Application

	Public Shared Sub Main()
		Dim factory As ChatterBotFactory = new ChatterBotFactory()
		
		Dim bot1 As ChatterBot = factory.Create(ChatterBotType.CLEVERBOT)
		Dim bot1session As ChatterBotSession = bot1.CreateSession()
		
		Dim bot2 As ChatterBot = factory.Create(ChatterBotType.PANDORABOTS, "b0dafd24ee35a477")
		Dim bot2session As ChatterBotSession = bot2.CreateSession()
	
		Dim s As String = "Hi"
		Do While true
		
			Console.WriteLine("bot1> " + s)
			
			s = bot2session.Think(s)
			Console.WriteLine("bot2> " + s)
				
			s = bot1session.Think(s)
		Loop
	End Sub
End Class

