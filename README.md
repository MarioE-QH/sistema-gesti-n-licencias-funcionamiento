# Sistema de Gestión de Licencias de Funcionamiento
(Cabe señalar que la información mostrada son datos simulados para mostrar la lógica de la información real)

Se implementó el desarrollo de un sistema de gestión de licencias de funcionamiento, que centralice la información, permitiendo su acceso en tiempo real, de esta manera que los fiscalizadores podrán verificar de forma inmediata el estado de cada local, optimizando el control, la fiscalización y la transparencia de los procesos municipales. Todo inicia desde la creación de la licencia de funcionamiento en la parte de administración; esta se recepciona en el área de defensa civil para luego proceder a adjuntar documentos de Certificación y Resolución en cada licencia correspondiente (estos documentos vencen cada 2 años por lo tanto se debe renovar dichos documentos), además el área de defensa civil gestiona información de estos documentos (fechas de recepción, salidas, etc.), por ultimo toda esta información se ve reflejada en la parte de inicio donde los fiscalizadores pueden ver que las licencias están activas, inactivas, las que están por vencer (tiene que renovar cada documentos 2 años), las que ya vencieron, las licencias que aún no tiene documentos (se muestra en la parte superior derecha, dar clic para ver) y pueden ver cada local con sus respectivos documentos adjuntos. Por otro lado se agrega un apartado para buscar las direcciones con google maps, facilitando a los fiscalizadores ir al establecimiento.

# Requisitos para implementar el sistema:
- XAMPP + MySQL
- Compositor
- vsCode
- Instalar LibreOffice (para convertir una plantilla de Word a PDF)
- Laravel 12 / php 8.2

# Vista login:
<img width="1920" height="912" alt="screencapture-127-0-0-1-8000-2025-12-14-11_39_22" src="https://github.com/user-attachments/assets/3516063e-1f32-4ef0-acfc-1a68919bea0f" />

# Vista inicio:
<img width="1920" height="2762" alt="screencapture-127-0-0-1-8000-main-2025-12-14-11_36_32" src="https://github.com/user-attachments/assets/6576fd27-4f0d-4491-894a-7303a36947af" />
<img width="1920" height="1056" alt="screencapture-127-0-0-1-8000-main-2025-12-14-11_37_55" src="https://github.com/user-attachments/assets/49023269-e976-4401-8248-f15fe563acb3" />

# Vista defensa civil:
<img width="1920" height="2762" alt="screencapture-127-0-0-1-8000-main-2025-12-14-11_36_32" src="https://github.com/user-attachments/assets/66420b5e-42f7-4496-b43d-27bfad4dc857" />

# Vista administración:
<img width="1920" height="1927" alt="screencapture-127-0-0-1-8000-admin-2025-12-14-11_38_12" src="https://github.com/user-attachments/assets/515681c6-11fc-40fc-92c3-0839f6e3e774" />
<img width="1920" height="1204" alt="screencapture-127-0-0-1-8000-admin-2025-12-14-11_38_42" src="https://github.com/user-attachments/assets/db2a2e76-bd94-4a6e-9477-8d66c5ec4137" />

# Vista mantenedores:
<img width="1920" height="1033" alt="screencapture-127-0-0-1-8000-mantenedores-2025-12-14-11_39_03" src="https://github.com/user-attachments/assets/b315ad4a-3aa2-4b7b-9a62-3210cf6517b7" />



