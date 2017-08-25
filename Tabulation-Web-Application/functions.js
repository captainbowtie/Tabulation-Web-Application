/* 
 * Copyright (C) 2017 allen
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
function $(id){
    return document.getElementById(id);
}
function O(obj)
{
  return document.getElementById(obj);
}
function S(obj)
{
  return O(obj).style
}
function C(name)
{
    var elements = document.getElementsByTagName('*');
  var objects  = [];

  for (var i = 0 ; i < elements.length ; ++i){
       if (elements[i].className == name){
           objects.push(elements[i]);
       }
      
  }
  return objects;
}
