import React from "react";

import {
  Form,
  Input,
  Radio,
  RadioChangeEvent,
  Space,
  Switch,
  Button,
  Spin,
  message,
} from "antd";
import MarkdownEditor from "@uiw/react-markdown-editor";
import axioas from "axios";

interface Data {
  browse?: string;
  create_time?: string;
  delete_time?: string | null;
  id?: string;
  is_release?: string;
  password?: string | null;
  subtitle?: string;
  title?: string;
  update_time?: string;
}

interface State {
  password: number;
  passwordInput: string;
  data: Data;
  spin: boolean;
}

const { TextArea } = Input;

export default class Edit extends React.Component<any, any> {
  public state: State = {
    password: 0,
    passwordInput: "none",
    spin: true,
    data: {},
  };

  constructor(props: any) {
    super(props);
    this.passwordChange = this.passwordChange.bind(this);
    this.save = this.save.bind(this);
    this.formChange = this.formChange.bind(this);
  }

  componentDidMount() {
    this.info();
  }

  async info() {
    const res = await axioas.get("/article/" + this.props.match.params.id);
    const data = res.data;
    this.setState({ data: data, spin: false });
    this.setState({ password: data.password == null ? 0 : 1 });
  }

  passwordChange(e: RadioChangeEvent) {
    this.setState({ passwordInput: e.target.value == 1 ? "block" : "none" });
  }

  async save() {
    this.setState({ spin: true });
    const res = await axioas.put("/article/" + this.props.match.params.id, {
      title: this.state.data.title,
      subtitle: this.state.data.subtitle,
    });

    this.setState({ spin: false });
    message.success("修改成功");
  }

  formChange(e: any, field: object) {
    this.setState({
      data: Object.assign(this.state.data, field),
    });
  }

  render() {
    return (
      <>
        <Spin spinning={this.state.spin}>
          <Form labelCol={{ span: 2 }}>
            <Form.Item label="标题">
              <Input
                value={this.state.data.title}
                onChange={(e) => this.formChange(e, { title: e.target.value })}
              />
            </Form.Item>
            <Form.Item label="副标题">
              <Input
                value={this.state.data.subtitle}
                onChange={(e) =>
                  this.formChange(e, { subtitle: e.target.value })
                }
              />
            </Form.Item>
            <Form.Item label="设置密码">
              <Space>
                <Radio.Group
                  defaultValue={this.state.password}
                  onChange={this.passwordChange}
                >
                  <Radio.Button value={0}>无密码</Radio.Button>
                  <Radio.Button value={1}>密码</Radio.Button>
                </Radio.Group>
                <Input style={{ display: this.state.passwordInput }} />
              </Space>
            </Form.Item>
            <Form.Item label="显示">
              <Switch
                checkedChildren="开启"
                unCheckedChildren="关闭"
                defaultChecked
              />
            </Form.Item>
            <Form.Item label="详情">
              <>
                <MarkdownEditor value="123165465" style={{ height: "700px" }} />
              </>
            </Form.Item>
            <Form.Item wrapperCol={{ span: 12, offset: 2 }}>
              <Button type="text" onClick={this.save}>
                保存
              </Button>
            </Form.Item>
          </Form>
        </Spin>
      </>
    );
  }
}
