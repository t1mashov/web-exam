
import json

txt = open('data.json', encoding='utf-8').read()
obj = json.loads(txt)

sql = '''
create table food_shop (
    id int primary key auto_increment,
    Name varchar(100),
    IsNetObject varchar(3),
    OperatingCompany varchar(100),
    TypeObject varchar(50),
    AdmArea text,
    District text,
    Address text,
    SeatsCount int,
    SocialPrivileges varchar(3),
    Longitude_WGS84 varchar(50),
    Latitude_WGS84 varchar(50)
);

'''

for o in obj[:5000:]:
    sql += f'''
insert into food_shop (Name, IsNetObject, OperatingCompany, TypeObject, AdmArea, District, Address, SeatsCount, SocialPrivileges, Longitude_WGS84, Latitude_WGS84)
values
('{o['Name'].replace("'", '`')}', '{o['IsNetObject']}', '{o['OperatingCompany'].replace("'", '`')}', '{o['TypeObject']}', '{o['AdmArea']}', '{o['District']}', '{o['Address'].replace("'", '`')}', {o['SeatsCount']}, '{o['SocialPrivileges']}', '{o['Longitude_WGS84']}', '{o['Latitude_WGS84']}');
'''

file = open('food_shop.sql', 'w', encoding='utf-8')
file.write(sql)
file.close()